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
    public function index(Request $request, ObjectManager $manager, OrderCustomerRepository $repo1, CustomerRepository $repo2, $id)
    {
        $order = $repo1->find($id);
        $numberOfTickets = $order->getNumberOfTickets();
        $allTickets = 2;
        
        $customer = new Customer();
        
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {   
            $customer   ->setOrderCustomer($order)
                        ->setTicketPrice(24.00) //A modifier
                        ->getDateOfBirthday()->setTime('00','00','00');
            
            $orderId = $order->getId();
            $allTickets += intval($repo2->findAllCustomers($orderId)); //Modifier le nom de la variable

            // $manager->persist($customer);
            // $manager->flush();

            // $allPrices = intval($repo2->findAllPrices($orderId));
            // dump($allPrices);

            //$id = $request->get('return');

           

            // return $this->redirectToRoute('order',[
            //         'id'                => $orderId,
            //         // 'numberOfTickets'   => $numberOfTickets
            //     ]);

            // return $this->redirectToRoute('customer',[
            //     'id'                => $id,
            //     'numberOfTickets'   => $numberOfTickets
            // ]);
        }
       
        // $i = $request->get('ticket');
        // $i = intval($i);
       
        //$i = $request->get('ticket');
        
       

        return $this->render('customer/customer.html.twig', [
            'form'              => $form->createView(),
            'numberOfTickets'   => $numberOfTickets,
            'ticket'            => $allTickets
            // 'orders'            => $order
        ]);
    }
}
