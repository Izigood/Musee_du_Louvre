<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/visiteurs", name="customer")
     */
    public function index()
    {
        return $this->render('customer/customer.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }
}
