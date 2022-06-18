<?php

namespace App\Controller;

use App\Entity\ConfirmUserEmailRequest;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\ConfirmUserEmailRequestManager;
use App\Service\UserManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    public function __construct()
    {
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserManager $userManager, MailerInterface $mailer, ConfirmUserEmailRequestManager $confirmEmailManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $userManager->add($user);

            // Create a confirm email user account request, store it and send it by email :
            $confirmEmailRequest = (new ConfirmUserEmailRequest())->setUser($user);
            $confirmEmailManager->add($confirmEmailRequest);

            $email = (new TemplatedEmail())
                ->from('noreply@snowtricks.com')
                ->to($user->getEmail())
                ->subject("Bienvenue sur SnowTricks !")
                ->htmlTemplate('registration/confirmation_email.html.twig')
                ->context([
                    'uuid' => $confirmEmailRequest->getUuid(),
                ])
            ;

            $mailer->send($email);

            $this->addFlash(
                'success',
                "Merci pour votre inscription ! Un email contenant un lien de validation vient de vous être envoyé. 
                Pour pouvoir vous connecter et profiter du site, veuillez valider votre compte en cliquant sur ce lien."
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
