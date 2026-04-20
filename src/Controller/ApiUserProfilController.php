<?php

namespace App\Controller;

use App\Repository\AdministrateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/api/user', name: 'api_user_')]
final class ApiUserProfilController extends AbstractController
{

    #[Route('/profil/{id}', name: 'profil', methods: ['GET'])]
    public function profil(int $id,AdministrateurRepository $administrateurRepository): JsonResponse 
    {
        $user = $administrateurRepository->find($id);

        if (!$user) {
            return $this->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }



        return $this->json([
            'success' => true,
            'message' => 'Profil récupéré avec succès',
            'user' => [
                'id'        => $user->getId(),
                'nom'       => $user->getNom(),
                'username'  => $user->getUsername(),
                'email'     => $user->getUsername(),    
                'telephone' => $user->getTelephone(),
                'roles'     => $user->getRoles(),
            ]
        ]);
    }
    #[Route('/profil/edit/{id}', name: 'profil_update', methods: ['PUT'])]
public function updateProfil(int $id, Request $request, AdministrateurRepository $administrateurRepository,EntityManagerInterface $em): JsonResponse
{
    $user = $administrateurRepository->find($id);

    if (!$user) {
        return $this->json([
            'success' => false,
            'message' => 'Utilisateur non trouvé'
        ], 404);
    }

    $data = json_decode($request->getContent(), true);
    
    // Mettre à jour les champs 
    if (isset($data['nom'])) {
        $user->setNom($data['nom']);
    }
    
    if (isset($data['telephone'])) {
        $user->setTelephone($data['telephone']);
    }
        if (isset($data['username'])) {
        //  si email existe
        $existingUser = $administrateurRepository->findOneBy(['username' => $data['username']]);
        if ($existingUser && $existingUser->getId() !== $user->getId()) {
            return $this->json([
                'success' => false,
                'message' => 'Cet email est déjà utilisé'
            ], 400);
        }
        $user->setUsername($data['username']);
    }

    
    try {
        $em->flush();
        
        return $this->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'user' => [
                'id'        => $user->getId(),
                'nom'       => $user->getNom(),
                'username'  => $user->getUsername(),
                'email'     => $user->getUsername(),
                'telephone' => $user->getTelephone(),
                'roles'     => $user->getRoles(),
            ]
        ]);
    } catch (\Exception $e) {
        return $this->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour'
        ], 500);
    }
}
}

