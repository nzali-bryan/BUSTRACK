<?php

namespace App\Controller;

use App\Entity\Administrateur;
use App\Form\AdministrateurType;
use App\Repository\AdministrateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/administrateur', name:'app_administrateur')]

final class AdministrateurController extends AbstractController
{

    #[Route('', name: 'app_administrateur_index')]
      
    public function index(AdministrateurRepository $administrateurRepository): Response
    {
        return $this->render('administrateur/index.html.twig', [
            // 'administrateurs' => $administrateurRepository->findAll(),
        ]);
    }

    #[Route('/creer', name: 'app_administrateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        $administrateur = new Administrateur();
        $form = $this->createForm(AdministrateurType::class, $administrateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $hasher->hashPassword(
                $administrateur,
                $administrateur->getPassword()
            );
            $administrateur->setPassword($hashedPassword);
            $entityManager->persist($administrateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_administrateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administrateur/new.html.twig', [
            'administrateur' => $administrateur,
            'form' => $form,
        ]);
    }
 
    #[Route('/{id}', name: 'app_administrateur_show', methods: ['GET'])]
    public function show(Administrateur $administrateur): Response
    {
        return $this->render('administrateur/show.html.twig', [
            'administrateur' => $administrateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_administrateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Administrateur $administrateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdministrateurType::class, $administrateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_administrateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administrateur/edit.html.twig', [
            'administrateur' => $administrateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_administrateur_delete', methods: ['POST'])]
    public function delete(Request $request, Administrateur $administrateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$administrateur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($administrateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_administrateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
