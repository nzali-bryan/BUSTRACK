<?php
// src/Controller/ApiLoginController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_api_login', methods: ['POST','GET'])]
    public function connexion(Request $request): JsonResponse
    {
        // 1. Récupérer le contenu brut de la requête
        $contenuBrut = $request->getContent();
        
        // 2. Convertir le JSON en tableau PHP
        $donnees = json_decode($contenuBrut, true);
        
        // 3. Vérifier si le JSON est valide
        if ($donnees === null) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'JSON invalide'
            ], 400);
        }
        dump($donnees);
        // 4. Récupérer chaque champ individuellement
        $nom = $donnees['nom'] ?? null;
        $password = $donnees['password'] ?? null;
        
        // 5. Vérifier que les champs sont présents
        if (!$nom || !$password) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Le nom et le mot de passe sont obligatoires'
            ], 400);
        }
        
        // 6. ICI tu peux faire ce que tu veux avec les données
        // Par exemple : chercher l'utilisateur en BDD, vérifier le mot de passe, etc.
        
        // 7. Retourner une réponse de succès avec les données reçues
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Données reçues avec succès',
            'donnees_recues' => [
                'nom' => $nom,
                'password' => $password
            ]
        ], 200);
    }
}