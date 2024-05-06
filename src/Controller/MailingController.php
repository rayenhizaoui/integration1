<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\MailForm;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;



class MailingController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }




    /*

    #[Route('/envoyer_email', name: 'envoyer_email')]
    public function envoyerEmail(Request $request, MailerInterface $mailer): Response
    {
        // Créer le formulaire
        $form = $this->createForm(MailForm::class);
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $data = $form->getData();

            // Récupérer l'adresse e-mail destinataire, l'objet et le corps de l'e-mail
            $toEmail = $data['recipient'];
            $subject = $data['subject'];
            $body = $data['body'];

            // Créer l'e-mail
            $email = (new Email())
                ->from('rayenhizaoui055@gmail.com')
                ->to($toEmail)
                ->subject($subject)
                ->text($body);

            // Envoyer l'e-mail
            try {
                $mailer->send($email);

                // Message de succès
                $this->addFlash('success', 'E-mail envoyé avec succès à ' . $toEmail);

                // Rediriger vers une autre page ou retourner une réponse
                //return $this->redirectToRoute('app_user_add');
            } catch (TransportExceptionInterface $e) {
                // Erreur lors de l'envoi du message
                $this->addFlash('error', 'Erreur : Impossible d\'envoyer l\'e-mail. Veuillez réessayer plus tard.');
            }
        }

        // Afficher le formulaire
        return $this->render('user/Mail.html.twig', [
            'form' => $form->createView()
        ]);
    }

*/











    #[Route('/envoyer_email', name: 'envoyer_email')]
    public function envoyerEmail(Request $request, MailerInterface $mailer): Response
    {
        // Créer le formulaire
        $form = $this->createForm(MailForm::class);
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $data = $form->getData();

            // Récupérer l'adresse e-mail destinataire, l'objet et le corps de l'e-mail
            $toEmail = $data['recipient'];
            $subject = $data['subject'];
            $body = $data['body'];

            // Créer l'e-mail
            $email = (new Email())
                ->from('rayenhizaoui055@gmail.com')
                ->to($toEmail)
                ->subject($subject)
                ->text($body);

            // Envoyer l'e-mail
            try {
                $mailer->send($email);

                // Message de succès
                $this->addFlash('success', 'E-mail envoyé avec succès à ' . $toEmail);

                // Rediriger vers une autre page ou retourner une réponse
                //return $this->redirectToRoute('app_user_add');
            } catch (TransportExceptionInterface $e) {
                // Erreur lors de l'envoi du message
                $this->addFlash('error', 'Erreur : Impossible d\'envoyer l\'e-mail. Veuillez réessayer plus tard.');
                $this->logger->error('Erreur lors de l\'envoi de l\'e-mail : ' . $e->getMessage());
            }
        }

        // Afficher le formulaire
        return $this->render('user/Mail.html.twig', [
            'form' => $form->createView()
        ]);
    }





    #[Route('/send-email', name: 'app_send_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('rayenhizaoui05@outlook.com')
            ->to('rayenhizaoui05@gmail.com')
            ->subject('Test d\'e-mail avec Symfony')
            ->text('Ceci est un e-mail de test envoyé depuis Symfony.');

        $mailer->send($email);

        return new Response('E-mail envoyé avec succès!');
    }
}
