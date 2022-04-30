<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewUserType;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

class UserController extends AbstractController
{
    /**
     * @Route("/user/profile/{id<\d+>?1}", name="app_user_profile", methods={"GET"})
     */
    public function showProfile(User $user): Response
    {
        return $this->render('user/show-profile.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/user/new", name="app_user_new")
     */
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserService $userService): Response
    {
        $user = new User();
        $form = $this->createForm(NewUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setCreatedAt(new DateTimeImmutable());
            $user->setUpdatedAt(new DateTimeImmutable());

            $userService->add($user);

            $this->addFlash('success', "Le nouvel utilisateur a été créé avec succès !");

            return $this->redirectToRoute('app_user_new');
        }
        return $this->render('user/new.html.twig', [
            'newUserType' => $form->createView()
        ]);
    }
}
