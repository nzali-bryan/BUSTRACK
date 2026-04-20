<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Repository\AdministrateurRepository;
use App\Repository\BusRepository;
use App\Repository\FavorisRepository;
use App\Repository\TrajetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ApiUserFavorisController extends AbstractController
{
#[Route('/api/favoris', name: 'api_favoris_list', methods: ['POST','GET'])]
public function apiListFavoris(Request $request, FavorisRepository $favorisRepository, BusRepository $busRepository): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    $userId = $data['userId'] ?? null;
    $userId=55;
   
    if (!$userId) {
        return $this->json(['success' => false, 'message' => 'Utilisateur non identifié'], 401);
    }
    
    $bus = $busRepository->findBusByUserFavoris($userId);
    $countFavoris = $favorisRepository->count(['administrateur' => $userId]);
    
    // Formater les données pour React Native
    $formattedBus = [];
    foreach ($bus as $item) {
        $formattedBus[] = [
            'id' => $item->getId(),
            'nom' => $item->getNom(),
            'capacite' => $item->getCapacite(),
            'etat' => $item->getEtat(),
            'direction' => $item->getDirection() ?? 'inconu',
            'trajet' => [
                'id' => $item->getTrajet()->getId(),
                'nom' => $item->getTrajet()->getNom(),
                'ligne' => $item->getTrajet()->getLigne(),
            ],
            'conducteur' => [
                'nom' => $item->getAdministrateur()->getNom(),
            ],
        ];
    }
    
    return $this->json([
        'success' => true,
        'favoris' => $formattedBus,
        'count' => $countFavoris,
    ],200);
}

#[Route('/api/favoris/delete', name: 'api_favoris_delete', methods: ['POST'])]
public function apiDeleteFavoris(Request $request, FavorisRepository $favorisRepository, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    $userId = $data['userId'] ?? null;
    $trajetId = $data['trajetId'] ?? null;
    
    if (!$userId || !$trajetId) {
        return $this->json(['success' => false, 'message' => 'Paramètres manquants'], 400);
    }
    
    $favori = $favorisRepository->findOneBy([
        'administrateur' => $userId,
        'trajets' => $trajetId
    ]);
    
    if (!$favori) {
        return $this->json(['success' => false, 'message' => 'Favori non trouvé'], 404);
    }
    
    $em->remove($favori);
    $em->flush();
    
    return $this->json(['success' => true, 'message' => 'Favori supprimé'],200);
}

#[Route('/api/favoris/delete-all', name: 'api_favoris_delete_all', methods: ['POST'])]
public function apiDeleteAllFavoris(Request $request,FavorisRepository $favorisRepository, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    $userId = $data['userId'] ?? null;
    
    if (!$userId) {
        return $this->json(['success' => false, 'message' => 'Utilisateur non identifié'], 401);
    }
    
    $allFavoris = $favorisRepository->findFavoris($userId);
    
    if (empty($allFavoris)) {
        return $this->json([
            'success' => false,
             'message' => 'Aucun favori à supprimer'
             ], 404);
    }
    
    foreach ($allFavoris as $favori) {
        $em->remove($favori);
    }
    $em->flush();
    
    return $this->json(['success' => true, 'message' => 'Tous les favoris ont été supprimés']);
}
#[Route('/api/favori/ajouter', name: 'api_favori_ajouter', methods: ['POST'])]
public function apiAjouterFavori(Request $request,FavorisRepository $favorisRepository, TrajetsRepository $trajetsRepository,AdministrateurRepository $administrateurRepository, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    $userId = $data['userId'] ?? null;
    $trajetId = $data['trajetId'] ?? null;
    
    if (!$userId || !$trajetId) {
        return $this->json(['success' => false, 'message' => 'Paramètres manquants'], 400);
    }
    
    // Récupérer l'utilisateur
    $user =$administrateurRepository->find($userId);
    if (!$user) {
        return $this->json(['success' => false, 'message' => 'Utilisateur non trouvé'], 404);
    }
    
    // Récupérer le trajet
    $trajet = $trajetsRepository->find($trajetId);
    if (!$trajet) {
        return $this->json(['success' => false, 'message' => 'Trajet non trouvé'], 404);
    }
    
    // Vérifier si déjà en favori
    $existingFavori = $favorisRepository->findOneBy([
        'administrateur' => $user,
        'trajets' => $trajet
    ]);
    
    if ($existingFavori) {
        return $this->json(['success' => false, 'message' => 'Déjà dans vos favoris'], 400);
    }
    
    // Créer le favori
    $favori = new Favoris();
    $favori->setAdministrateur($user);
    $favori->setTrajets($trajet);
    
    $em->persist($favori);
    $em->flush();
    
    return $this->json([
        'success' => true,
        'message' => 'Trajet ajouté aux favoris',
        'favoriId' => $favori->getId()
    ],200);
}
}
