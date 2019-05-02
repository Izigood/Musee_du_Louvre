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
        
        $form = $this->createForm(OrderCustomerType::class, $order);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $format         = new \DateTime(date('m/d/Y'), new \DateTimeZone('Europe/Paris'));

            $order          ->setDateOfOrder($format)
                            ->setOrderStatus('En cours')
                            ->setTotalPrice(0.00);
                            
            $securityFirstname = strip_tags(ucfirst($order->getFirstname()));
            $filterFirstname = $order->setFirstname($securityFirstname);
            
            $securityLastname = strip_tags(strtoupper($order->getLastname()));
            $filterLastname = $order->setLastname($securityLastname);

            $dateOfVisit    = $order->getDateOfVisit()->setTime('00','00','00');
            $tickets        = intval($repo->findAllTicketsByDateOfVisit($dateOfVisit));
            $total          = intval($order->getNumberOfTickets());
            $totalTickets   = $tickets + $total;

            $info = $order->getNumberOfTickets();

            switch ($totalTickets)
            {
                case ($totalTickets > 1000):
                    return $this->redirectToRoute('order', [
                        $this->addFlash(
                            'danger',
                            "Désolé, il n'est plus possible de commander de billets !"
                        )
                    ]);
                    break;
            }
            
            $timeGap        = $format->diff($dateOfVisit);
            $startDate      = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $interval       = new DateInterval('P1D');

            if($timeGap->d < $interval->d)
            {
                $time = $startDate->format('H');

                if($time >= 18 )
                {
                    $this->addFlash(
                        'danger',
                        "Oups, il est trop tard pour commander aujourd'hui !"
                    );
                    return $this->redirectToRoute('order');
                    die;
                }
                if($time >= 14)
                {
                    $halfDay = $order->setHalfDay(true);
                    $this->addFlash(
                        'success',
                        "Votre visite est prévue pour la demi-journée !"
                    );
                }
            }
            else
            {
                $halfDay = $order->getHalfDay();

                if($halfDay == true)
                {
                    $this->addFlash(
                        'success',
                        "Votre visite est prévue pour la demi-journée !"
                    );
                }
            }

            $manager->persist($order);
        }
        $manager->flush();

        $id = $order->getId();

        if(isset($id))
        {
            $this->addFlash(
                        'success',
                        "Vos billets sont réservés. Ravis de vous compter parmis nous !"
                    );

            return $this->redirectToRoute('customer', [
                'id'            => $id
            ]);
        }

        return $this->render('order_customer/order_customer.html.twig', [
            'form'          => $form->createView()
        ]);
    }
}
