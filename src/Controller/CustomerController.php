<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Service\PricesService;
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
     * @Route("/billet/id={id}", name="customer")
     * 
     * @return Response
     */
    public function index(Request $request, ObjectManager $manager, OrderCustomerRepository $repo1, CustomerRepository $repo2, $id)
    {
        
        $order = $repo1->find($id);
        $numberOfTickets = $order->getNumberOfTickets(); //A VOIR
        $orderId = $order->getId();
        $allTickets = 1;
        
        $customer = new Customer();
        
        $form = $this->createForm(CustomerType::class, $customer);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {   
            $customer   ->setOrderCustomer($order)
                        ->getDateOfBirthday()->setTime('00','00','00');

            $startDate = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $endDate = $customer->getDateOfBirthday();

            $age = intval(date_diff($startDate, $endDate)->format('%Y'));
            dump($age);

            $halfDay = $order->getHalfDay();
            dump($halfDay);

            $reduced = $customer->getReducedPrice();
            dump($reduced);

            $price = new PricesService;
            $price = $price->definePrice($age, $halfDay, $reduced);
            $customer->setTicketPrice($price);
            dump($price);

            $manager->persist($customer);
            
        }

        $allPrices = intval($repo2->findAllPrices($orderId));
        $order->setTotalPrice($allPrices);
        dump($order);

        $manager->persist($order);
        $manager->flush();

        $allTickets += intval($repo2->findAllCustomers($orderId));

        if($allTickets > $numberOfTickets)
            {
                //Envoyer la commande vers Stripe
                //Envoyer email récapitulatif
            }

        return $this->render('customer/customer.html.twig', [
            'form'              => $form->createView(),
            'numberOfTickets'   => $numberOfTickets,
            'ticket'            => $allTickets
        ]);
    }
}
