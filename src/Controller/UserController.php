<?php

namespace App\Controller;

use App\Repository\BusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/interface/passager', name: 'app_user')]
    public function index(BusRepository $busRepository ,Request $request): Response
    {
        $countbus = $busRepository->findBusActifs([
            'en_route',
            'chargement',
        ]);
        $zone= $request->query->get('zone');
        
        if($zone){
            $Allbus= $busRepository->findZoneBus($zone);
        }else{
            $Allbus= $busRepository->findAll();
        }
    
        
        
        return $this->render('user/index.html.twig', [
           'NombreDeBus'=>$countbus,
           'AllBus'=>$Allbus,
        ]);
    }
}
