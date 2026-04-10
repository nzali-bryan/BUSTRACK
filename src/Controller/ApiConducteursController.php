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
    #[Route('/conducteurs', name: 'app_api_conducteurs', methods:['POST','GET'])]
    public function index(Request $request,PointRepository $pointRepository, AdministrateurRepository $administrateurRepository, TrajetsRepository $trajetsRepository, BusRepository $busRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'] ?? null;
        
        $user=$administrateurRepository->find(['id'=>$id]);
        // récupération d'un bus lié au conducteurs

        $busUser=$busRepository->findBusDriver($id);
      
        $idbus=$busUser->getId();
        $trajetUser=$trajetsRepository->findTrajetDriver($idbus);  
        

        $idTrajets=$trajetUser->getId();

        $pointUserASC=$pointRepository->findPointDriverASC($idTrajets);
        $pointUserDESC=$pointRepository->findPointDriverDESC($idTrajets);


        // dd($user,$busUser,$trajetUser,$pointUserASC,$pointUserDESC);

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
                'ligne'=> $trajetUser->getLigne(),
            ],
            'pointASC' => [
                'id' => $pointUserASC->getId(),
                'nom' => $pointUserASC->getNom(),
            ],
            'pointDESC' => [
                'id' => $pointUserDESC->getId(),
                'nom' => $pointUserDESC->getNom(),
            ],
        ], 200);
    }
}
