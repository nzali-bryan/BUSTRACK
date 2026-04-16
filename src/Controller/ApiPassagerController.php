<?php

namespace App\Controller;

use App\Repository\AdministrateurRepository;
use App\Repository\BusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\TrajetsRepository;
use Symfony\Component\Routing\Attribute\Route;

final class ApiPassagerController extends AbstractController
{
    #[Route('/api', name: 'api_trajets', methods:['GET'])]
    public function index(Request $request,AdministrateurRepository $administrateurRepository,TrajetsRepository $trajetsRepository,BusRepository $busRepository): JsonResponse
    {
        $countbus = $busRepository->findBusActifs([
            'En Route',
            'chargement',
            'Incident',
        ]);
        $Allbus = $busRepository->findAllBusNotNullAndTrajets();

        $AllDriver=$administrateurRepository->findDriverHaveBus();
        $dataBus=[];
            foreach ($Allbus as $bus) {
                 $dataBus[]=[
                    'nom'=> $bus->getNom(),
                    'capacite'=>$bus->getCapacite(),
                    'etat'=>$bus->getEtat(),
                    'direction'=>$bus->getDirection(),
                 ];
            }
        $data=[];
            foreach ($Allbus as $bus) {
                 $driver = $bus->getAdministrateur();
                 $data[]=[
                    'id'=>$driver->getId(),
                    'nom'=>$driver->getNom(),
                 ];
            }
        dd($countbus,$Allbus,$AllDriver,$data);
        return $this->json(
            [
            'success'=> true,
            'message'=>'Données bien traitées',
            'bus'=>$dataBus,
            'driver'=>$data,
            ]
        );
    }
}
