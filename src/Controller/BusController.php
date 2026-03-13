<?php

namespace App\Controller;

use App\Entity\Bus;
use App\Form\BusType;
use App\Repository\BusRepository;
use App\Repository\TrajetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bus')]
final class BusController extends AbstractController
{
    #[Route(name: 'app_buss_index', methods:  ['GET', 'POST'])]
    public function index(Request $request,BusRepository $busRepository,TrajetsRepository $trajetsRepository,EntityManagerInterface $entityManager): Response
    {
        $bu = new Bus();
        $form = $this->createForm(BusType::class, $bu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bu);
            $entityManager->flush();

            return $this->redirectToRoute('app_buss_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('bus/index.html.twig', [
            'trajets'=> $trajetsRepository->findAll(),
            'buss' => $busRepository->findAll(),
             'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_buss_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bu = new Bus();
        $form = $this->createForm(BusType::class, $bu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bu);
            $entityManager->flush();

            return $this->redirectToRoute('app_buss_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bus/new.html.twig', [
            'bus' => $bu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_buss_show', methods: ['GET'])]
    public function show(Bus $bu): Response
    {
        return $this->render('bus/show.html.twig', [
            'bus' => $bu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_buss_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bus $bu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BusType::class, $bu);
        
        $form->handleRequest($request);
        $data = $request->request->all()['buss'];

        $bu->setNom($data['nom']);
        $bu->setEtat($data['etat']);
         $bu->setCapacite($data['capacite']);
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
}
