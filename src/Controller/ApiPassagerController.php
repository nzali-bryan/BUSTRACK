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
    public function index(
        BusRepository $busRepository,
        AdministrateurRepository $administrateurRepository
    ): JsonResponse {

        // Récupération des bus avec leurs relations
        $buses = $busRepository->findAllWithRelations(); // Assure-toi que cette méthode existe et joint bien les données

        $data = [];

        foreach ($buses as $bus) {
            $driver = $bus->getAdministrateur(); // Relation entre Bus et Administrateur (conducteur)

            $data[] = [
                'id'          => $bus->getId(),
                'nom'         => $bus->getNom(),
                'capacite'    => $bus->getCapacite(),
                'etat'        => $bus->getEtat() ?? 'Inactif',
                'direction'   => $bus->getDirection() ?? 'Direction non définie',
                'conducteur'  => $driver ? $driver->getNom() : 'Non assigné',
                'ligne'       => $bus->getTrajet() ? $bus->getTrajet()->getLigne() : 'N/A',
                'trajetNom'   => $bus->getTrajet() ? $bus->getTrajet()->getNom() : 'Trajet principal',
                'trajetId'   => $bus->getTrajet() ? $bus->getTrajet()->getId():'' ,
                'zone'        => $bus->getTrajet() ? $bus->getTrajet()->getZone(): 'Douala', 
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