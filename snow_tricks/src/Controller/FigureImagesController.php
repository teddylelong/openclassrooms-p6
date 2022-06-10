<?php

namespace App\Controller;

use App\Entity\FigureImages;
use App\Security\Voter\FigureVoter;
use App\Service\FigureImagesManager;
use App\Service\FigureManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FigureImagesController extends AbstractController
{
    /**
     * @Route("/figure/images/delete/{id<\d+>}", name="app_figure_image_delete", methods={"DELETE"})
     */
    public function delete(Request $request, FigureImages $image, FigureImagesManager $imagesManager): JsonResponse
    {
        $figure = $image->getFigure();
        $this->denyAccessUnlessGranted(FigureVoter::DELETE, $figure);

        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])) {
            $fileName = $image->getFilename();
            unlink($this->getParameter('images_directory').'/images/'.$fileName);

            $imagesManager->delete($image);

            return new JsonResponse(['success' => 1]);
        }
        return new JsonResponse(['error' => 'Token Invalide'], 400);
    }
}
