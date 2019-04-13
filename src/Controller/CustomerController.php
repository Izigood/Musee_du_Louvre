<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Entity\OrderCustomer;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    /**
     * @Route("/visiteurs/id={id}/nbre-tickets={ticketsId}", name="customer")
     * 
     * @return Response
     */
    public function index(Request $request, ObjectManager $manager, CustomerRepository $repo, $id)
    {
        
        $order = new OrderCustomer;
        // $id = $order->getId();
        // dump($id);
        dump($order);
        
        

        // $ticketsId = $repoOrder->findAllTicketsById($id);
        // $ticketsId = intval($ticketsPerId);
        // dump($ticketsId);

        $customer = new Customer();
        
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //$format = new \DateTime(date('m/d/Y'), new \DateTimeZone('Europe/Paris'));
            
            $dateOfBirthday = $customer->getDateOfBirthday();
            $birthday = $dateOfBirthday->setTime('00','00','00');
            dump($birthday);

            $customer->setTicketPrice(24);
            $price = $customer->getTicketPrice();
            dump($price);
       

            // ;

            // $customer->setOrderCustomer(298);
            // $orderCustomer = $customer->getOrderCustomer();
            // dump($orderCustomer);

            //dump($customer);

            // $manager->persist($customer);
            // $manager->flush();

            //return $this->redirectToRoute('customer');
        }

        return $this->render('customer/customer.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
