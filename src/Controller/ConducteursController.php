<?php

namespace App\Controller;

use App\Entity\Administrateur;
use App\Form\AjoutModifDriverType;
use App\Repository\AdministrateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ConducteursController extends AbstractController
{
    #[Route('/conducteurs', name: 'app_conducteurs_index')]
    public function index(Request $request,AdministrateurRepository $administrateurRepository,EntityManagerInterface $entityManager,UserPasswordHasherInterface $hasher): Response

    {
        $administrateur = new Administrateur();
        $form = $this->createForm(AjoutModifDriverType::class, $administrateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $administrateur->setRoles(['ROLE_DRIVER']);
           
            $hashedPassword = $hasher->hashPassword(
                $administrateur,
                $administrateur->getPassword()
            );
            $administrateur->setPassword($hashedPassword);
            $entityManager->persist($administrateur);
            // $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_conducteurs_index', [], Response::HTTP_SEE_OTHER);
        }
        $conducteur=$administrateurRepository->findDriver();

        return $this->render('conducteurs/index.html.twig', [
            'conducteurs'=> $conducteur,
            'form'=>$form->createView(),
        ]);
    }

    // Modif conducteur 
    #[Route('/{id}/edit', name: 'app_conducteurs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Administrateur $administrateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AjoutModifDriverType::class, $administrateur);
        $form->handleRequest($request);
        $data = $request->request->all()['trajets'];

        $administrateur->setNom($data['nom']);
        $administrateur->setTelephone($data['telephont']);
        $administrateur->setUsername($data['username']);
        $entityManager->flush();
        return $this->redirectToRoute('app_trajets_index', [], Response::HTTP_SEE_OTHER);
        
    }

    #[Route('/{id}', name: 'app_conducteurs_delete', methods: ['POST'])]
    public function delete(Request $request, Administrateur $administrateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$administrateur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($administrateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_conducteurs_index', [], Response::HTTP_SEE_OTHER);
    }

}
