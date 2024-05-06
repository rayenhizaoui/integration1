<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer extends AbstractController
{
    #[Route('/send_email1', name: 'send_email')]
    public function sendEmail(Request $request, ValidatorInterface $validator): Response
    {
        // Récupérer les données du formulaire s'il est soumis
        $formData = $request->request->all();

        // Si le formulaire est soumis
        if (!empty($formData['send'])) {
            $toId = $formData['toid'];
            $subject = $formData['subject'];
            $message = $formData['message'];

            // Valider les données du formulaire
            $errors = $validator->validate($formData);

            // Si des erreurs de validation sont trouvées
            if (count($errors) > 0) {
                // Renvoyer une réponse avec un message d'erreur
                $errorMessage = (string) $errors;
                return new Response($errorMessage, Response::HTTP_BAD_REQUEST);
            }

            // Créer une instance de PHPMailer
            $mail = new PHPMailer(true);

            // Configurer les paramètres SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rayenlorkha1@gmail.com';
            $mail->Password = 'vijxbklqgybjxoqx';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Définir l'expéditeur et le destinataire de l'e-mail
            $mail->setFrom('rayenlorkha1@gmail.com', 'RAYEN');
            $mail->addAddress($toId);

            // Définir le sujet et le corps de l'e-mail
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Envoyer l'e-mail
            if ($mail->send()) {
                // Renvoyer une réponse avec un message de succès
                return new Response("Message Sent", Response::HTTP_OK);
            } else {
                // Renvoyer une réponse avec un message d'erreur
                $error = "Mailer Error: " . $mail->ErrorInfo;
                return new Response($error, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        // Si le formulaire n'est pas soumis, rendre la vue Twig
        return $this->render('email/send_email.html.twig');
    }
}
