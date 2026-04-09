<?php

namespace App\Controller;

use App\Repository\AdministrateurRepository;
use App\Repository\BusRepository;
use App\Repository\PointRepository;
use App\Repository\TrajetsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

 #[Route('/api', name: '')]
final class ApiConducteursController extends AbstractController
{
    #[Route('/conducteurs/{id}', name: 'app_api_conducteurs', methods:['POST','GET'])]
    public function index($id,Request $request,PointRepository $pointRepository, AdministrateurRepository $administrateurRepository, TrajetsRepository $trajetsRepository, BusRepository $busRepository): JsonResponse
    {
        $user=$administrateurRepository->find(['id'=>$id]);
        // récupération d'un bus lié au conducteurs

        $busUser=$busRepository->findBusDriver($id);

        // récupération du trajet lié au bus
        foreach ($busUser as $bus ){ 
            $data[] = [
                'id' => $bus->getId(),
            ];
            
        }
        
        $idbus=$data[0]['id'];
        $trajetUser=$trajetsRepository->findTrajetDriver($idbus);  
        
        // récupération des points liés au trajets 
        foreach ($trajetUser as $trajet ){ 
            $data[] = [
                'id' => $trajet->getId(),
            ];
            
        }
        $idTrajets=$data[0]['id'];

        $pointUser=$pointRepository->findPointDriver($idTrajets);

        // dd($user,$busUser,$trajetUser,$pointUser);

        return $this->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'email'=> $user->getUsername(),
                'roles' => $user->getRoles(),
                'telephone'=>$user->getTelephone(),
            ],
            'bus' => [
                'id' => $busUser->getId(),
                'nom' => $busUser->getNom(),
                'capacite'=> $busUser->getCapacite(),
                'etat' => $busUser->getEtat(),
                'direction'=>$busUser->getDirection(),
            ],
            'trajet' => [
                'id' => $trajetUser->getId(),
                'nom' => $trajetUser->getNom(),
                'ligne'=> $trajetUser->getUsername(),
            ],
            'point' => [
                'id' => $pointUser->getId(),
                'nom' => $pointUser->getNom(),
            ],
        ], 200);
    }
}
