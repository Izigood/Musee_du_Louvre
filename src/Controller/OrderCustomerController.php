<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderCustomerController extends AbstractController
{
    /**
     * @Route("/order/customer", name="order_customer")
     */
    public function index()
    {
        return $this->render('order_customer/index.html.twig', [
            'controller_name' => 'OrderCustomerController',
        ]);
    }
}
