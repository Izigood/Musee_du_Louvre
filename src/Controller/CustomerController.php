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
     * @Route("/commande/id={id}", name="customer")
     * @Route("/id={id}", name="customer_main")
     * 
     * @return Response
     */
    public function index(Request $request, ObjectManager $manager, OrderCustomerRepository $repo1, CustomerRepository $repo2, $id)
    {
        $new = $repo1->findId();

        if(isset($id) && $new == $id)
       {
            $order = $repo1->find($id);
            $numberOfTickets = $order->getNumberOfTickets();
            $allTickets = 1;
            
            $customer = new Customer();
            
            $form = $this->createForm(CustomerType::class, $customer);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {   
                $customer   ->setOrderCustomer($order)
                            ->getDateOfBirthday()->setTime('00','00','00');

                $securityFirstname = strip_tags(ucfirst($customer->getFirstname()));
                $filterFirstname = $customer->setFirstname($securityFirstname);
                            
                $securityLastname = strip_tags(strtoupper($customer->getLastname()));
                $filterLastname = $customer->setLastname($securityLastname);

                $securityCountry = strip_tags(ucfirst($customer->getCountry()));
                $filterCountry = $customer->setCountry($securityCountry);
               
                $startDate = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
                $endDate = $customer->getDateOfBirthday();

                $age = intval(date_diff($startDate, $endDate)->format('%Y'));

                $halfDay = $order->getHalfDay();

                $reduced = $customer->getReducedPrice();

                $price = new PricesService;
                $price = $price->definePrice($age, $halfDay, $reduced);
                $customer->setTicketPrice($price);

                $manager->persist($customer);
                
                $this->addFlash(
                    'success',
                    "Votre billet au nom de $securityFirstname $securityLastname est bien enregistré !" //A modfier
                );
            }

            $allPrices = intval($repo2->findAllPrices($id));
            $order->setTotalPrice($allPrices);
            
            $manager->persist($order);
            $manager->flush();

            $allTickets += intval($repo2->findAllCustomers($id));

            if($allTickets > $numberOfTickets)
            {   
                $this->addFlash(
                    'success',
                    "Nous sommes heureux de vous confimer l'enregistrement de tous vos billets !" //A modifier
                );
                    
                return $this->redirectToRoute('verification',[
                    'id'            => $id
                ]);
            }
            return $this->render('customer/customer.html.twig', [
                'form'              => $form->createView(),
                'numberOfTickets'   => $numberOfTickets,
                'ticket'            => $allTickets
            ]);
       }
       else
       {
        return $this->redirectToRoute('error_page');
       }   
    }
}
