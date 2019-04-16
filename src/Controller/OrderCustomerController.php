<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use DateTimeZone;
use App\Service\PricesService;
use App\Entity\Customer;
use App\Entity\OrderCustomer;
use App\Form\UserCustomerType;
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
                            ->setOrderStatus('En cours') // A modifier
                            ->setTotalPrice(12.00); // A modifier                

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
                    $halfDay = false; //A vérifier
                }
            }
            $manager->persist($order);
        }

        $customer = new Customer();
        
        $formCustomer = $this->createForm(UserCustomerType::class, $customer);

        $formCustomer->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $customer   ->setOrderCustomer($order)
                        ->setFirstname($order->getFirstname())
                        ->setLastname($order->getLastname())
                        ->setTicketPrice(24) //A modifier
                        ->getDateOfBirthday()->setTime('00','00','00');

            $manager->persist($customer);

            $this->addFlash(
                'success',
                "Vos billets sont réservés, vous pouvez poursuivre votre commande !"
            );
        }
        $manager->flush();

        // $reduced = $customer->getReducedPrice();
        
        // $age = 5;
        // $halfDay = $order->getHalfDay();
        

        // $price = new PricesService;
        // $prices = $price->definePrice($age, $halfDay, $reduced);
        

        
        // dump($age);
        // dump($halfDay);
        // dump($reduced);
        // dump($prices);




        // dump($customer);

        // dump($total);

        // if($total <= 1)
        // {
        //     $info = "Commande immédiate"; //A modifier
        //     dump($info);

        //     return $this->redirectToRoute('order');
        //     die;
        // }
        // else
        // {   
            $id = $order->getId();
            dump($id);

            return $this->redirectToRoute('customer', [
                'id'        => $id
            ]);
        // }
        

        return $this->render('order_customer/order_customer.html.twig', [
            'form'          => $form->createView(),
            'formCustomer'  => $formCustomer->createView()
            // 'total'         => $total
        ]);
    }
}
