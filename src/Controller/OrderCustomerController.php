<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use DateTimeZone;
use App\Service\PricesService;
use App\Entity\Customer;
use App\Entity\OrderCustomer;
use App\Form\OrderCustomerType;
use App\Repository\OrderCustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderCustomerController extends AbstractController
{
    /**
     * @Route("/commande", name="order")
     * @Route("/", name="order_main")
     * 
     * @return Response
     */
    public function index(Request $request,ObjectManager $manager, OrderCustomerRepository $repo)
    {
        $order = new OrderCustomer();
        //$id = $order->setId(0); //A voir
        
        $form = $this->createForm(OrderCustomerType::class, $order);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $format         = new \DateTime(date('m/d/Y'), new \DateTimeZone('Europe/Paris'));

            $order          ->setDateOfOrder($format)
                            ->setOrderStatus('En cours')
                            ->setTotalPrice(0.00);           

            $dateOfVisit    = $order->getDateOfVisit()->setTime('00','00','00');
            $tickets        = intval($repo->findAllTicketsByDateOfVisit($dateOfVisit));
            $total          = intval($order->getNumberOfTickets());
            $totalTickets   = $tickets + $total;

            switch ($totalTickets)
            {
                case ($totalTickets > 1000):
                    return $this->redirectToRoute('order', [
                        $this->addFlash(
                            'warning',
                            "Désolé, il n'est plus possible de commander de billets"
                        )
                    ]);
                    break;
            }

            // if($totalTickets <= 1000)
            // {
            //     $rest = 1000 - $totalTickets;
            //     $message = "Nous pouvons vous proposer ". $rest ." billets de disponibles"; // A modifier
            // }

            // $halfDay = $order->getHalfDay();
            // $dateOfDay = $format;
            
            $timeGap = $dateOfVisit->diff($format);
            $startDate = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $interval = new DateInterval('P1D');
            
            if($timeGap == $interval)
            {
                $info = $startDate->format('H');

                // if($info >= 18 )
                // {
                //     $this->addFlash(
                //         'danger',
                //         "Oups, il est trop tard pour commander aujourd'hui !"
                //     );
                //     return $this->redirectToRoute('order');
                //     die;
                // }
                if($info >= 14)
                {
                    $halfDay = $order->setHalfDay(true);
                    $this->addFlash(
                        'success',
                        "Billet(s) commandé(s) pour la demi-journée !"
                    );
                }
                else
                {
                    $halfDay = false;
                }
            }
            $manager->persist($order);
        }

        $manager->flush();

        // $this->addFlash(
        //     'success',
        //     "Vos billet(s) sont réservé(s) !"
        // );

        $id = $order->getId();

        return $this->redirectToRoute('customer', [
                'id'            => $id
            ]);
       
        return $this->render('order_customer/order_customer.html.twig', [
            'form'          => $form->createView(),
        ]);
    }
}
