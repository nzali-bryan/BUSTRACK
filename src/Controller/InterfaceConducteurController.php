<?php

namespace App\Controller;

use App\Entity\Bus;
use App\Form\DirectionBusType;
use App\Form\StatusBusType;
use App\Repository\ConducteursRepository;
use App\Repository\PointRepository;
use App\Repository\BusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BusType;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

final class InterfaceConducteurController extends AbstractController
{
    #[Route('/interface/conducteur/{id}', name: 'app_interface_conducteur')]
    public function index(Request $request,$id, ConducteursRepository $conducteursRepository, EntityManagerInterface $em,PointRepository $pointRepository): Response
    {
    $conducteur= $conducteursRepository->find($id);
    $bus=$conducteur->getBus();
    $trajet=$bus->getTrajet();
    $pointASC = $pointRepository->findByTrajetIdASC($trajet->getId());
    $pointDESC=$pointRepository->findByTrajetIdDESC($trajet->getId());
   

    $form=$this->createForm(StatusBusType::class, $bus);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($conducteur);
        $em->flush();
    }
    $formDirection=$this->createForm(DirectionBusType::class, $bus);
    $formDirection->handleRequest($request);
    if ($formDirection->isSubmitted() && $formDirection->isValid()) {
        $em->persist($formDirection);
        $em->flush();
    }
    {
        return $this->render('interface_conducteur/index.html.twig', [
            'conducteur'=> $conducteur,
            'bus'=> $bus,
            'trajet'=>$trajet,
            'pointASC'=>$pointASC,
            'pointDESC'=>$pointDESC,
            'form'=> $form->createView(),
            'formDirection'=> $formDirection->createView(),

                ]);
    }

}


#[Route('/bus/{id}/update-direction', name: 'update_direction_bus', methods: ['POST'])]
public function updateDirection($id,Request $request, Bus $bus, EntityManagerInterface $entityManager): Response
{
    // Vérifie le token CSRF
    $submittedToken = $request->request->get('_token');
    if (!$this->isCsrfTokenValid('update_direction' . $bus->getId(), $submittedToken)) {
        throw $this->createAccessDeniedException('Token invalide');
    }

    // Récupère la direction choisie
    $direction = $request->request->get('direction');
    if ($direction) {
        $bus->setDirection($direction); // direction est juste une string ici
        $entityManager->flush();
    }
return $this->redirectToRoute('app_interface_conducteur', [
    'id' => $bus->getConducteurs()->getId()
]);
}
}
