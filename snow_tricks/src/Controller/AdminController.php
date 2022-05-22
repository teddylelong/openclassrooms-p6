<?php

namespace App\Controller;

use App\Security\Voter\AdminVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="app_admin")
     */
    public function dashboard(): Response
    {
        $this->denyAccessUnlessGranted(AdminVoter::VIEW, $this->getUser());

        return $this->render('admin/dashboard.html.twig');
    }
}
