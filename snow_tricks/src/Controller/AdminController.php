<?php

namespace App\Controller;

use App\Security\Voter\AdminVoter;
use App\Service\AdminService;
use App\Service\CommentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="app_admin")
     */
    public function dashboard(AdminService $adminService): Response
    {
        $this->denyAccessUnlessGranted(AdminVoter::VIEW, $this->getUser());

        $countPendingComments = $adminService->countPendingComments();
        $countPendingFigures  = $adminService->countPendingFigures();

        return $this->render('admin/dashboard.html.twig', [
            'countPendingComments' => $countPendingComments,
            'countPendingFigures' => $countPendingFigures,
        ]);
    }
}
