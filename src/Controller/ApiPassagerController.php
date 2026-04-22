<?php

namespace App\Controller;

use App\Repository\AdministrateurRepository;
use App\Repository\BusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
final class ApiPassagerController extends AbstractController
{
    #[Route('', name: 'api_accueil', methods: ['GET'])]
    public function index(BusRepository $busRepository,AdministrateurRepository $administrateurRepository): JsonResponse 
    {
        $buses = $busRepository->findAllWithRelations(); 

        $data = [];

        foreach ($buses as $bus) {
            $driver = $bus->getAdministrateur(); 
            $trajet=$bus->getTrajet();
            $data[] = [
                'id'          => $bus->getId(),
                'nom'         => $bus->getNom(),
                'capacite'    => $bus->getCapacite(),
                'etat'        => $bus->getEtat() ?? 'Inactif',
                'direction'   => $bus->getDirection() ?? 'Direction non définie',
                'conducteur'  => $driver->getNom() ?? 'Non assigné',
                'ligne'       => $trajet->getLigne() ?? '?',
                'trajetNom'   => $trajet->getNom() ?? 'Trajet principal',
                'trajetId'    => $trajet->getId() ?? '?' ,
                'zone'        => $trajet->getZone() ?? 'Douala', 
            ];
        }

        return $this->json([
            'success' => true,
            'message' => 'Liste des bus récupérée avec succès',
            'total'   => count($data),
            'bus'     => $data,
        ]);
    }
}