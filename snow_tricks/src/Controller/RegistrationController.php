<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\UserManager;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelper;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserManager $userManager): Response
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

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('noreply@localhost.test', 'SnowTricks'))
                    ->to($user->getEmail())
                    ->subject('Bienvenue chez SnowTricks !')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            $this->addFlash('success', "Merci pour votre inscription ! Un email contenant un lien de validation vient de vous être envoyé. Pour pouvoir vous connecter et profiter du site, veuillez valider votre compte.");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre adresse email à été vérifiée avec succès. Merci ! :)');

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/verify/resend", name="app_verify_resend_email")
     */
    public function resendVerifyEmail(Request $request, UserRepository $userRepository)
    {
        if ($request->isMethod('POST')) {

            $email = $request->getSession()->get('non_verified_email');
            $user = $userRepository->findOneBy(['email' => $email]);
            if (!$user) {
                throw $this->createNotFoundException("Cette adresse email n'est liée à aucun utilisateur");
            }

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('noreply@localhost.test', 'SnowTricks'))
                    ->to($user->getEmail())
                    ->subject('Bienvenue chez SnowTricks !')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // TODO: in a real app, send this as an email!
            $this->addFlash('success', 'Un email contenant un nouveau lien de validation vous a été envoyé.');

            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/resend_verify_email.html.twig');
    }
}
