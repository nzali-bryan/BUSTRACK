<?php

namespace App\Controller;

use App\Entity\Bus;
use App\Form\BusType;
use App\Repository\AdministrateurRepository;
use App\Repository\BusRepository;
use App\Repository\TrajetsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bus')]
final class BusController extends AbstractController
{
    #[Route(name: 'app_buss_index', methods:  ['GET', 'POST'])]
    public function index(Request $request,BusRepository $busRepository,TrajetsRepository $trajetsRepository,AdministrateurRepository $administrateurRepository,EntityManagerInterface $entityManager): Response
    {
        $bu = new Bus();
       
        $form = $this->createForm(BusType::class, $bu);
        $form->handleRequest($request);
      

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bu);
            $entityManager->flush();

            return $this->redirectToRoute('app_buss_index', [], Response::HTTP_SEE_OTHER);
        }
        $driverNotBus=$administrateurRepository->findDriverNotBus();
        dump($driverNotBus);
        return $this->render('bus/index.html.twig', [
            'trajets'=> $trajetsRepository->findAll(),
            'buss' => $busRepository->findBy([],['nom'=>'ASC']),
            'conducteurs' => $administrateurRepository->findDriver(),
            'form' => $form,
            'DriverNotBus'=>$driverNotBus,
           
        ]);
    }


    #[Route('/{id}/edit', name: 'app_buss_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bus $bu,TrajetsRepository $trajetRepository, AdministrateurRepository $administrateurRepository, EntityManagerInterface $entityManager): Response
    {


        $form = $this->createForm(BusType::class, $bu);
        
        $form->handleRequest($request);
        $data = $request->request->all()['buss'];
        
        $admin=$data['conducteur'];
        $administrateur=$administrateurRepository->find($admin);
        $trajet=$data['trajet'];
        $trajetRecovery=$trajetRepository->find($trajet);
        $bu->setNom($data['nom']);
        $bu->setEtat($data['etat']);
        $bu->setCapacite($data['capacite']);
        $bu->setTrajet($trajetRecovery);

        $bu->setAdministrateur($administrateur);

         $entityManager->persist($bu);
         $entityManager->flush();
        return $this->redirectToRoute('app_buss_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_buss_delete', methods: ['POST'])]
    public function delete(Request $request, Bus $bu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bu->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_buss_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/api/trajets', name: 'app_buss_api', methods: ['POST','GET'])]
    public function api(Request $request,TrajetsRepository $trajetsRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = [];
        $trajets=$trajetsRepository->findAll();

        foreach ($trajets as $trajet) {
            $data[] = [
                'id' => $trajet->getId(),
                'nom' => $trajet->getNom(),
                // 'bus' => $busList
            ];
        }

        return $this->json($data);
    }
}
