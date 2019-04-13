<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Entity\OrderCustomer;
use App\Repository\CustomerRepository;
use App\Repository\OrderCustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    /**
     * @Route("/visiteurs/id={id}", name="customer")
     * 
     * @return Response
     */
    public function index(Request $request, ObjectManager $manager, OrderCustomerRepository $repo, $id)
    {
        $order = $repo->find($id);
        $numberOfTickets = $order->getNumberOfTickets();
        $lastnameFirst = $order->getLastname();
        $firstnameFirst = $order->getFirstname();
  
        
        $customer = new Customer();
        
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //$format = new \DateTime(date('m/d/Y'), new \DateTimeZone('Europe/Paris'));
            
            $orderId = $customer->setOrderCustomer($order);
            dump($numberOfTickets);
            // $orderCustomer = $customer->setOrderCustomer($idOrder);
            // $orderCustomer = $customer->getOrderCustomer();
            // dump($orderCustomer);
            
            $dateOfBirthday = $customer->getDateOfBirthday();
            $birthday = $dateOfBirthday->setTime('00','00','00');
            dump($birthday);

            $customer->setTicketPrice(24); // A modifier
            $price = $customer->getTicketPrice();
            dump($price);
       

            $manager->persist($customer);
            $manager->flush();

            $id = $order->getId();

            dump($customer);

            // for($i = 0; $i <= ($numberOfTickets - 1); $i++)
            // {
            //     $ticket = $i+1;
            //     dump($ticket);
            // }


            // return $this->redirectToRoute('customer',[
            //     'id'                => $id,
            //     // 'numberOfTickets'   => $numberOfTickets,
            //     // 'firstname'         => $firstnameFirst,
            //     // 'lastname'          => $lastnameFirst
            // ]);
        }

        return $this->render('customer/customer.html.twig', [
            'form'              => $form->createView(),
            'numberOfTickets'   => $numberOfTickets,
            'firstname'         => $firstnameFirst,
            'lastname'          => $lastnameFirst
        ]);
    }
}
