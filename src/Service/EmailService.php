<?php
// src/Service/EmailService.php

namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Twig\Environment as TwigEnvironment;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\EventListener\MessageListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer, TwigEnvironment $twig, EventDispatcherInterface $eventDispatcher)
    {
        $messageListener = new MessageListener(null, new BodyRenderer($twig));
        $eventDispatcher->addSubscriber($messageListener);

        $transport = Transport::fromDsn('smtp://localhost', $eventDispatcher);
        $this->mailer = new Mailer($transport, null, $eventDispatcher);
    }

    public function sendTemplatedEmail()
    {
        $email = (new TemplatedEmail())
            ->htmlTemplate('email/signup.html.twig')
            ->context([
                'expiration_date' => new \DateTimeImmutable('+7 days'),
                'username' => 'foo',
            ]);

        $this->mailer->send($email);
    }
}
