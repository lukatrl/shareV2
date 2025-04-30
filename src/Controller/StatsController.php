<?php

namespace App\Controller;

use App\Repository\FichierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    #[Route('/admin/stats', name: 'stats_page')] // Route pour afficher la page
    public function showStats(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('stats/stats.html.twig');
    }

    #[Route('/stats/files', name: 'stats_files')]
    public function filesStats(FichierRepository $fichierRepository): JsonResponse
    {
        $stats = $fichierRepository->countFilesByUser();
        return new JsonResponse($stats, 200, ['Content-Type' => 'application/json']);    }
}
