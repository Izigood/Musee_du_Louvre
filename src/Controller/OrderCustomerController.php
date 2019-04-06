<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderCustomerController extends AbstractController
{
    /**
     * @Route("/commande", name="order")
     * @Route("/", name="order_main")
     * 
     */
    public function index()
    {
        return $this->render('order_customer/order_customer.html.twig', [
            'controller_name' => 'OrderCustomerController',
        ]);
    }
}
