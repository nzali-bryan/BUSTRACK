<?php

namespace App\Controller;

use App\Entity\Conducteurs;
use App\Form\ConducteursType;
use App\Repository\ConducteursRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/conducteurs' )]
final class ConducteursController extends AbstractController
{
    #[Route(name: 'app_conducteurs_index', methods: ['GET', 'POST'])]
    public function index(ConducteursRepository $conducteursRepository,Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response

    {
        $conducteur = new Conducteurs();
        $form = $this->createForm(ConducteursType::class, $conducteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $conducteur,
                $conducteur->getPassword()
            );

    
            $conducteur->setPassword($hashedPassword);
            $entityManager->persist($conducteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_conducteurs_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('conducteurs/index.html.twig', [
            'conducteurs' => $conducteursRepository->findAll(),
             'form' => $form,
        ]);
    }



    #[Route('/new', name: 'app_conducteurs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {
        $conducteur = new Conducteurs();
        $form = $this->createForm(ConducteursType::class, $conducteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $conducteur,
                $conducteur->getPassword()
            );

    
            $conducteur->setPassword($hashedPassword);
            $entityManager->persist($conducteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_conducteurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conducteurs/index.html.twig', [
            'conducteur1' => $conducteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conducteurs_show', methods: ['GET'])]
    public function show(Conducteurs $conducteur): Response
    {
        return $this->render('conducteurs/show.html.twig', [
            'conducteur' => $conducteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conducteurs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conducteurs $conducteur, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(ConducteursType::class, $conducteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $hashedPassword = $passwordHasher->hashPassword(
                $conducteur,
                $conducteur->getPassword()
            );

    
            $conducteur->setPassword($hashedPassword);
            $entityManager->persist($conducteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_conducteurs_index', [], Response::HTTP_SEE_OTHER);
        }

        // return $this->render('conducteurs/edit.html.twig', [
        //     'conducteur' => $conducteur,
        //     'form' => $form,
        // ]);
        return $this->redirectToRoute('app_conducteurs_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_conducteurs_delete', methods: ['POST'])]
    public function delete(Request $request, Conducteurs $conducteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conducteur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($conducteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_conducteurs_index', [], Response::HTTP_SEE_OTHER);
    }
}
