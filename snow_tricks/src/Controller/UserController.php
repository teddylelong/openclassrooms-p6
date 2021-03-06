<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserAvatarType;
use App\Form\UserType;
use App\Security\Voter\AdminVoter;
use App\Security\Voter\UserVoter;
use App\Service\FigureManager;
use App\Service\FileUploader;
use App\Service\UserManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

class UserController extends AbstractController
{
    /**
     * @Route("/user/", name="app_user_index")
     */
    public function index(UserManager $userManager): Response
    {
        $this->denyAccessUnlessGranted(AdminVoter::VIEW, $this->getUser());

        return $this->render('user/index.html.twig', [
            'users' => $userManager->findAll()
        ]);
    }

    /**
     * @Route("/user/profile/{id<\d+>}", name="app_user_profile")
     */
    public function showProfile(Request $request, User $user, UserManager $userManager, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $figures = $user->getFigures();

        $form = $this->createForm(UserAvatarType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted(UserVoter::UPDATE_AVATAR, $user);

            $avatar = $form->get('avatar')->getData();

            /** @var UploadedFile $avatar */
            $fileName = $fileUploader->upload($avatar, '/avatars');
            $user->setAvatar($fileName);

            $userManager->add($user);

            $this->addFlash('success', "Votre avatar a été mis à jour avec succès ! :)");
            $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
        }

        return $this->render('user/show-profile.html.twig', [
            'user' => $user,
            'figures' => $figures,
            'avatarType' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/new", name="app_user_new")
     */
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserManager $userManager, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setIsVerified(true);

            $avatar = $form->get('avatar')->getData();

            if (null !== $avatar) {
                /** @var UploadedFile $avatar */
                $fileName = $fileUploader->upload($avatar, '/avatars');
                $user->setAvatar($fileName);
            }

            $userManager->add($user);

            $this->addFlash('success', "Le nouvel utilisateur a été créé avec succès !");

            return $this->redirectToRoute('app_user_index');
        }
        return $this->render('user/new.html.twig', [
            'userType' => $form->createView()
        ]);
    }

    /**
     * @Route("user/edit/{id}", name="app_user_edit")
     */
    public function edit(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher, UserManager $userManager, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /* If password field is empty, keep the old one in DB
             * Else, hash & record the new */
            $originalPswd = $user->getPassword();
            $user->setPassword($originalPswd);

            if (!empty($form->get('password')->getData())) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            }
            $user->setUpdatedAt(new DateTimeImmutable());

            $avatar = $form->get('avatar')->getData();

            if (null !== $avatar) {
                /** @var UploadedFile $avatar */
                $fileName = $fileUploader->upload($avatar, '/avatars');
                $user->setAvatar($fileName);
            }

            $userManager->add($user);

            $this->addFlash('success', "L'utilisateur a été mis à jour avec succès !");

            return $this->redirectToRoute('app_user_edit', [
                'id' => $user->getId()
            ]);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'editForm' => $form,
        ]);
    }

    /**
     * @Route("/user/delete/{id<\d+>}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserManager $userManager, FigureManager $figureManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {

            // Check if user are not deleting himself
            if($user === $this->getUser()) {
                $this->addFlash('danger', "Vous ne pouvez pas vous supprimer vous-même...");
                return $this->redirectToRoute('app_user_index');
            }

            // Get all user's figures and set author to null
            $figures = $user->getFigures();
            if ($figures) {
                foreach ($figures as $figure ) {
                    $figure->setUser(null);
                    $figureManager->add($figure);
                }
            }

            $userManager->delete($user);
            $this->addFlash('success', "L'utilisateur a été supprimé avec succès !");
        }

        return $this->redirectToRoute('app_user_index');
    }
}

