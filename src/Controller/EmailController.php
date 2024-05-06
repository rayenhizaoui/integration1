<?php

// src/Controller/EmailController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class EmailController extends AbstractController
{
    #[Route('/send_email', name: 'send_email')]
    public function sendEmail(): Response
    {
        $transport = Transport::fromDsn('smtp://localhost');
        $mailer = new Mailer($transport);

        $email = (new Email())
            ->from('rayenhizaoui05@gmail.com')
            ->to('rayenhizaoui055@gmail.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return $this->render('email/success.html.twig');
    }
}
