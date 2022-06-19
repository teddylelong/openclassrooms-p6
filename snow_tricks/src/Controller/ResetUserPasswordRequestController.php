<?php

namespace App\Controller;

use App\Entity\ResetUserPasswordRequest;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Service\ResetUserPasswordRequestManager;
use App\Service\UserManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetUserPasswordRequestController extends AbstractController
{
    /**
     * @Route("/reset-password", name="app_forgot_password_request")
     */
    public function request(Request $request, UserManager $userManager, ResetUserPasswordRequestManager $passwordManager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userManager->findOneByMail($email);

            if (!$user) {
                $this->addFlash('danger', "Aucun compte n'est lié à cette adresse");
                return $this->redirectToRoute('app_forgot_password_request');
            }

            $passwordRequest = (new ResetUserPasswordRequest())->setUser($user);
            $passwordManager->add($passwordRequest);

            $email = (new TemplatedEmail())
                ->from('noreply@snowtricks.com')
                ->to($user->getEmail())
                ->subject("Réinitialisation de votre mot de passe")
                ->htmlTemplate('reset_password/email.html.twig')
                ->context([
                    'uuid' => $passwordRequest->getUuid()
                ])
            ;

            $mailer->send($email);

            $this->addFlash(
                'success',
                "Un email vous a été envoyé, il contient un lien permettant de réinitialiser votre mot de passe."
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset/{uuid}", name="app_reset_password")
     */
    public function reset(Request $request, UserPasswordHasherInterface $userPasswordHasher, ResetUserPasswordRequestManager $passwordManager, UserManager $userManager, string $uuid = null): Response
    {
        if (null === $uuid) {
            $this->addFlash('danger', "La requête de réinitialisation du mot de passe est invalide. Veuillez réessayer.");
            return $this->redirectToRoute('app_login');
        }

        $passwordRequest = $passwordManager->findOneByUuid($uuid);

        if (!$passwordRequest) {
            $this->addFlash('danger', "La requête de réinitialisation du mot de passe est introuvable. Veuillez réessayer.");
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $passwordManager->delete($passwordRequest);

            $user = $userManager->find($passwordRequest->getUser());
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $userManager->add($user);

            $this->addFlash('success', "Votre mot de passe à bien été modifié. Vous pouvez désormais vous connecter.");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
