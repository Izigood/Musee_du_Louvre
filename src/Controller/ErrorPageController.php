<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ErrorPageController extends AbstractController
{
    /**
     * @Route("/error_page", name="error_page")
     */
    public function index()
    {
        return $this->render('error_page/error_page.html.twig');
    }
}
