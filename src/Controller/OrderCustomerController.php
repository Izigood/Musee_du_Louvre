<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use DateTimeZone;
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
            
            $format = new \DateTime(date('m/d/Y'), new \DateTimeZone('Europe/Paris'));

            $order->setDateOfOrder($format);
            $order->setOrderStatus('En cours'); // A modifier
            $order->setTotalPrice(12.00); // A modifier

            $halfDay = $order->getHalfDay();
      
            $dateOfVisit = $order->getDateOfVisit();
            $visit = $dateOfVisit->setTime('00','00','00');

            $tickets = $repo->findAllTicketsByDateOfVisit($visit);
            dump($tickets);
            $total = intval($order->getNumberOfTickets());
            dump($total);
            $tickets = intval($tickets);
            dump($tickets);
            $totalTickets = $tickets + $total;
            dump($totalTickets);
 
            if($totalTickets <= 1000)
            {
                $rest = 1000 - $totalTickets;
                $mot = "Nous pouvons vous proposer ". $rest ." billets de disponibles"; // A modifier
      
            }

            if($totalTickets > 1000)
            {
                $mot = "Oups, il n'est plus possible de commander de billets"; //A modifier
                //return $this->redirectToRoute('order');
            }

            $dateOfDay = $format;
            
            $gap = $visit->diff($dateOfDay);
            $startDate = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $interval = new DateInterval('P1D');

            if($gap == $interval)
            {
                $info = $startDate->format('H');

                if($info >= 18 )
                {
                    $mot1 = "Désolé, mais il est trop tard pour commander aujourd'hui !";
                    //return $this->redirectToRoute('order');
                    dump($mot1);
                }

                if($info >= 14)
                {
                    $halfDay = $order->setHalfDay(true);
                    //indiquer un message
                }
            }

            $manager->persist($order);
            $manager->flush();

            $id = $order->getId();
            $ticketsId = $repo->findAllTicketsById($id);
            $ticketsId = intval($ticketsId);
            
        
            return $this->redirectToRoute('customer', [
                'id'        => $id,
                'ticketsId' => $ticketsId
            ]);
        }

        
   
        

        return $this->render('order_customer/order_customer.html.twig', [
            'form'  => $form->createView(),
        ]);
    }
}
