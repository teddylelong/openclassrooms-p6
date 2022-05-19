<?php

namespace App\Controller;

use App\Entity\FigureImages;
use App\Repository\FigureImagesRepository;
use App\Security\Voter\FigureVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FigureImagesController extends AbstractController
{
    /**
     * @Route("/figure/images/delete/{id<\d+>}", name="app_figure_image_delete")
     */
    public function delete(Request $request, FigureImages $image, FigureImagesRepository $figureImagesRepository): JsonResponse
    {
        $figure = $image->getFigure();
        $this->denyAccessUnlessGranted(FigureVoter::DELETE, $figure);

        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])) {
            $fileName = $image->getFilename();
            unlink($this->getParameter('images_directory').'/'.$fileName);

            $figureImagesRepository->remove($image);

            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
