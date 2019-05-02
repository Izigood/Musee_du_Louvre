<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Customer;
use App\Entity\OrderCustomer;
use App\Service\UserNotification;
use App\Repository\CustomerRepository;
use App\Repository\OrderCustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderDetailController extends AbstractController
{
    /**
     * @Route("/verification/id={id}", name="verification")
     * 
     * @return Response
     */
    public function index(ObjectManager $manager, OrderCustomerRepository $repo1, CustomerRepository $repo2, UserNotification $notification, $id)
    {
        $new = $repo1->findId();

        if(isset($id) && $new == $id)
       {
        
            $order = $repo1->find($id);
            $orderId = $order->getId();
            $dateOfOrder = $order->getDateOfOrder();
            $userEmail = $order->getEmail();

            $orderName = "Commande de " . $order->getFirstname() . " " . $order->getLastname();

            $allOrders = $repo2->findBy(['orderCustomer' => $orderId]);
            
            $totalPrices = $repo2->findAllPrices($orderId);
            $totalPrices = $order->setTotalPrice($totalPrices);
            $totalPrices = $order->getTotalPrice();

            $amountForStripe = $order->getTotalPrice() * 100;
            
            $min = 1;
            $max = 1000000000000000;
            $codeInt = rand($min, $max);
        
            $codeAlpha = "abcdefghijklmnopqrstuvwxyx";
            $codeRandom = $codeAlpha[rand(0,25)] . $codeAlpha[rand(0,25)] . $codeAlpha[rand(0,25)];
            
            $codeFinal = strtoupper($codeRandom) . $codeInt;

            $manager->persist($order);    
            $manager->flush();

            if(isset($_POST['stripeToken']))
            {
                try{
                    \Stripe\Stripe::setApiKey("sk_test_Q7vnG2tBjRDET2XyLgBvSV3T00NCJwkf4P");

                    $token = $_POST['stripeToken'];

                    $charge = \Stripe\Charge::create([
                        'amount' => $amountForStripe,
                        'currency' => 'eur',
                        'description' => $orderName,
                        'source' => $token
                    ]);
                }

                catch (\Stripe\Error\Card $e)
                {
                    if($e != null)
                    {   
                        return $this->redirectToRoute('verification', [
                            'id'            => $id,
                            $this->addFlash(
                                'danger',
                                "Désolé, votre commande a échouée, veuillez saisir à nouveau les numéros de votre carte bancaire !"
                            )
                        ]);
                    }
                }
                
                $order->setOrderStatus('Terminée');
                $manager->persist($order);    
                $manager->flush();

                if ($order->getOrderStatus() == 'Terminée')
                {
                    $notification->notify($dateOfOrder, $codeFinal, $allOrders, $totalPrices, $userEmail);

                    return $this->redirectToRoute('order', [
                        $this->addFlash(
                            'success',
                            "Félicitations, votre commande a bien été prise en compte ! À très bientôt au Musée du Louvre :-)"
                        )
                    ]);
                }   
                if ($order->getOrderStatus() == 'En cours')
                {
                    return $this->redirectToRoute('error_page', [
                        $this->addFlash(
                            'danger',
                            "Désolé, mais votre commande n'a pas été prise en compte !"
                        )
                    ]);
                }  
            }
            return $this->render('order_detail/order_detail.html.twig', [
                'allOrders'     => $allOrders,
                'totalPrices'   => $totalPrices
            ]);
        }
        else
        {
            return $this->redirectToRoute('error_page');
        }
    }
}
