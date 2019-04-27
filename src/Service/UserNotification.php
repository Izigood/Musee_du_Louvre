<?php

namespace App\Service;

use Twig\Environment;
use App\Entity\Customer;
use App\Entity\OrderCustomer;
use App\Controller\OrderCustomerController;

class UserNotification 
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __CONSTRUCT(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify($dateOfOrder, $codeFinal, $allOrders, $totalPrices, $userEmail)
    {
        $message = (new \Swift_Message('Votre billet - Musée du Louvre'))
            ->setFrom('noreply@server.com')
            ->setTo($userEmail)
            ->setReplyTo($userEmail);

        $image = $message->embed(\Swift_Image::fromPath('img/logo.jpg'));

        $message->setBody($this->renderer->render('contact/contact.html.twig', [
            'dateOfOrder'   => $dateOfOrder,
            'codeFinal'     => $codeFinal,
            'allOrders'     => $allOrders,
            'totalPrices'   => $totalPrices,
            'img'           => $image
        ]), 'text/html');
        
        $this->mailer->send($message);
    }
}