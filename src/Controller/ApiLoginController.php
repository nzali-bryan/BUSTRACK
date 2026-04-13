<?php
// src/Controller/ApiLoginController.php

namespace App\Controller;

use App\Entity\Administrateur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\AdministrateurRepository;

#[Route('/' , name: '')]
class ApiLoginController extends AbstractController
{
    #[Route('api/login', name: 'api_login', methods: ['POST','GET'])]
    public function connexion(
        Request $request,
        EntityManagerInterface $em,
        AdministrateurRepository $administrateurRepository,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);
        $nom = $data['nom'] ?? null;
        $password = $data['password'] ?? null;

        if (!$nom || !$password) {
            return $this->json([
                'echec' => false,
                'message' => 'Le nom et le mot de passe sont obligatoires'
            ], 400);
        }

        $user = $administrateurRepository->findOneBy(['username' => $nom]);
    
        if (!$user) {
            return $this->json([
                'echec' => false,
                'message' => 'Nom incorrect'
            ], 401);
        }
        if (!$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json([
                'echec' => false,
                'message' => 'Mot de passe incorrect'
            ], 401);
        }

        return $this->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'roles' => $user->getRoles()
            ]
        ], 200);
    }

    #[Route('api/register' , name: 'api_register' , methods: ['POST', 'GET'])]
    public function register(Request $request, AdministrateurRepository $administrateurRepository, EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher):JsonResponse{

    $data =json_decode($request->getContent(), true);
    $nom=$data['nom'] ?? null;
    $username=$data['email'] ?? null;
    $password=$data['password'] ?? null;
    if (!$nom || !$username || !$password) {
        return $this->json([
            'success' => false,
            'message' => 'Tous les champs (nom, email, mot de passe) sont requis'
        ], 400);
    }
    $existingUser = $administrateurRepository->findOneBy(['username' => $username]);
    if ($existingUser) {
        return $this->json([
            'success' => false,
            'message' => 'Cet email est déjà utilisé'
        ], 409);
    }

    $user= new Administrateur();
    $user->setNom($nom);
    $user->setUsername($username);

    $hashedpassword=$passwordHasher->hashPassword($user, $password);
    $user->setPassword($hashedpassword);
    $em->persist($user);
    $em->flush();


        return $this->json([
            'success'=>true,
            'message'=>'Inscription réussie',
            'id' => $user->getId(),
        ], 200);
    }
}