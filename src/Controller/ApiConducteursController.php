<?php

namespace App\Controller;

use App\Repository\AdministrateurRepository;
use App\Repository\BusRepository;
use App\Repository\PointRepository;
use App\Repository\TrajetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

 #[Route('/api', name: '')]
final class ApiConducteursController extends AbstractController
{


    #[Route('/conducteurs', name: 'app_api_conducteurs', methods: ['POST', 'GET'])]
public function index(Request $request,AdministrateurRepository $administrateurRepository,BusRepository $busRepository,TrajetsRepository $trajetsRepository,PointRepository $pointRepository,EntityManagerInterface $em): JsonResponse 
{
    $data = json_decode($request->getContent(), true) ?? [];

    $driverId = $data['id'] ?? null;

    if (!$driverId) {
        return $this->json([
            'success' => false,
            'message' => 'ID conducteur manquant'
        ], 400);
    }

    // Récupération des données avec protection contre null
    $user = $administrateurRepository->find($driverId);
    if (!$user) {
        return $this->json([
            'success' => false,
            'message' => 'Conducteur non trouvé'
        ], 404);
    }

    $busUser = $busRepository->findBusDriver($driverId);
    if (!$busUser) {
        return $this->json([
            'success' => false,
            'message' => 'Aucun bus assigné à ce conducteur'
        ], 404);
    }

    $trajetUser = $trajetsRepository->findTrajetDriver($busUser->getId());
    if (!$trajetUser) {
        return $this->json([
            'success' => false,
            'message' => 'Aucun trajet trouvé pour ce bus'
        ], 404);
    }

    $pointUserASC = $pointRepository->findPointDriverASC($trajetUser->getId());
    $pointUserDESC = $pointRepository->findPointDriverDESC($trajetUser->getId());

    // Mise à jour direction/état si envoyés
    $direction = $data['direction'] ?? null;
    $etat = $data['etat'] ?? null;

    if ($direction !== null) {
        $busUser->setDirection($direction);
    }
    if ($etat !== null) {
        $busUser->setEtat($etat);
    }
    if ($direction !== null || $etat !== null) {
        $em->flush();
    }

    return $this->json([
        'success' => true,
        'message' => 'Données chargées avec succès',
        'user' => [
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'email' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'telephone' => $user->getTelephone(),
        ],
        'bus' => [
            'id' => $busUser->getId(),
            'nom' => $busUser->getNom(),
            'capacite' => $busUser->getCapacite(),
            'etat' => $busUser->getEtat(),
            'direction' => $busUser->getDirection(),
        ],
        'trajet' => [
            'id' => $trajetUser->getId(),
            'nom' => $trajetUser->getNom(),
            'ligne' => $trajetUser->getLigne(),
        ],
        'pointASC' => $pointUserASC ? [
            'id' => $pointUserASC->getId(),
            'nom' => $pointUserASC->getNom(),
        ] : null,
        'pointDESC' => $pointUserDESC ? [
            'id' => $pointUserDESC->getId(),
            'nom' => $pointUserDESC->getNom(),
        ] : null,
    ], 200);
}

    #[Route('/bus/update' , name:'app_api_edit_etat', methods:['POST','GET'])]
    public function editBus (Request $request, BusRepository $busRepository,EntityManagerInterface $em):JsonResponse
    {
        $data=json_decode($request->getContent(), true);
        $direction=$data['direction'] ?? null; 
        $etat=$data['etat'] ?? null;
        $id=$data['busId'];
        $bus=$busRepository-> find($id);
        if ($direction){
            $bus->setDirection($direction);
            $em->flush();
        }
        if($etat){
            $bus->setEtat($etat);
            $em->flush();
        }
        
        return $this->json([
            'success'=>true,
            'message'=>'connexion réussie'
        ],200);
    }
}
