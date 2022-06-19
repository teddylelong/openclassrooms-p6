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
                    'user_id' => $user->getId()
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

    /**
     * @Route("/activate/resend", name="app_verify_resend_email")
     */
    public function resendEmail(Request $request, UserManager $userManager, ConfirmUserEmailRequestManager $confirmEmailManager, MailerInterface $mailer)
    {
        if ($request->isMethod('POST')) {

            $email = $request->getSession()->get('non_verified_email');
            $user = $userManager->findOneBy(['email' => $email]);
            if (!$user) {
                throw $this->createNotFoundException("Cette adresse email n'est liée à aucun utilisateur");
            }

            $confirmEmailRequest = $confirmEmailManager->findOneByUser($user);

            if (!$confirmEmailRequest) {
                $confirmEmailRequest = (new ConfirmUserEmailRequest())->setUser($user);
                $confirmEmailManager->add($confirmEmailRequest);
            }

            // Check expiration datetime
            $now = new \DateTimeImmutable('now');
            $expireDate = $confirmEmailRequest->getExpiresAt();

            if ($now > $expireDate) {
                $confirmEmailManager->delete($confirmEmailRequest);
                $confirmEmailRequest = (new ConfirmUserEmailRequest())->setUser($user);
                $confirmEmailManager->add($confirmEmailRequest);
            }

            $email = (new TemplatedEmail())
                ->from('noreply@snowtricks.com')
                ->to($user->getEmail())
                ->subject("Bienvenue sur SnowTricks !")
                ->htmlTemplate('registration/confirmation_email.html.twig')
                ->context([
                    'uuid' => $confirmEmailRequest->getUuid(),
                    'user_id' => $user->getId()
                ])
            ;

            $mailer->send($email);

            $this->addFlash(
                'success',
                "Un email contenant un nouveau lien de validation vous a été envoyé."
            );

            return $this->redirectToRoute('app_login');

        }
        return $this->render('registration/resend_verify_email.html.twig');
    }

    /**
     * @Route("/activate/{id}-{uuid}", name="app_activate_account", methods={"GET"})
     */
    public function activate(Request $request, UserManager $userManager, ConfirmUserEmailRequestManager $confirmEmailManager): Response
    {
        $uuid = $request->get('uuid');
        $userId = $request->get('id');

        // Check if this request exists in DB
        $user = $userManager->find($userId);
        $confirmEmailRequest = $confirmEmailManager->findOneByUuid($uuid);

        if (!$user || !$confirmEmailRequest) {
            $this->addFlash(
                'danger',
                "Votre compte n'a pas pu être activé car la requête est invalide. Veuillez en demander une nouvelle en vous connectant à votre compte."
            );
            return $this->redirectToRoute('app_login');
        }

        // Check expiration datetime
        $now = new \DateTimeImmutable('now');
        $expireDate = $confirmEmailRequest->getExpiresAt();

        if ($now > $expireDate) {
            $confirmEmailManager->delete($confirmEmailRequest);
            $this->addFlash(
                'danger',
                "Cette requête a expirée. Veuillez en demander une nouvelle en vous connectant à votre compte."
            );
            return $this->redirectToRoute('app_login');
        }

        // Request is valid !
        $confirmEmailManager->delete($confirmEmailRequest);

        $user->setIsVerified(true);
        $userManager->add($user);

        $this->addFlash(
            'success',
            "Votre adresse email à été vérifiée avec succès. Connectez-vous afin de profiter de l'ensemble des fonctionnalités du site ! :)"
        );

        return $this->redirectToRoute('app_login');
    }
}
