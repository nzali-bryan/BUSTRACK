<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\TrajetsRepository;
use Symfony\Component\Routing\Attribute\Route;

final class ApiPassagerController extends AbstractController
{
        // #[Route('api/trajets', name: 'api_trajets', methods: ['POST','GET'], requirements: ['slash' => '/?'])]
        #[Route('/api/trajets{slash}', name: 'api_trajets', requirements: ['slash' => '/?'])]
    public function api(Request $request,TrajetsRepository $trajetsRepository): JsonResponse
    {
        $data = [];
        $trajets=$trajetsRepository->findAll();

        foreach ($trajets as $trajet) {
            $data[] = [
                'id' => $trajet->getId(),
                'nom' => $trajet->getNom(),
                // 'bus' => $busList
            ];
        }

        return $this->json($data);
    }
}
