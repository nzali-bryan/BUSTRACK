<?php

namespace App\Controller;

use App\Form\StatusBusType;
use App\Repository\ConducteursRepository;

use App\Repository\BusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

final class InterfaceConducteurController extends AbstractController
{
    #[Route('/interface/conducteur/{id}', name: 'app_interface_conducteur')]
    public function index(Request $request,$id, ConducteursRepository $conducteursRepository, EntityManagerInterface $em): Response
    {
    $conducteur= $conducteursRepository->find($id);
    $bus=$conducteur->getBus();
    $trajet=$bus->getTrajet();

    $form=$this->createForm(StatusBusType::class, $bus);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($conducteur);
        $em->flush();

        

    }
    {
        return $this->render('interface_conducteur/index.html.twig', [
            'conducteur'=> $conducteur,
            'bus'=> $bus,
            'trajet'=>$trajet,
            'form'=> $form->createView(),

                ]);
    }
}
}
