<?php

namespace App\Controller;

use App\Entity\Administrateur;
use App\Entity\Bus;
use App\Entity\Point;
use App\Entity\Trajets;
use App\Form\TrajetsType;
use App\Form\PointType;
use App\Form\BusType;
use App\Repository\BusRepository;
use App\Form\AdministrateurType;
use App\Form\AjoutModifDriverType;
use App\Repository\AdministrateurRepository;
use App\Repository\PointRepository;
use App\Repository\TrajetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
 
#[Route('administrateur', name:'')]


final class AdministrateurController extends AbstractController
{



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


    #[IsGranted('ROLE_ADMIN')]
    #[Route('', name: 'app_administrateur_index')]
       public function index(Request $request,BusRepository $busRepository,PointRepository $pointRepository,TrajetsRepository $trajetsRepository,EntityManagerInterface $entityManager,UserPasswordHasherInterface $hasher): Response
    {

    // ajouter administrateur
        $administrateur = new Administrateur();
        $formDriver = $this->createForm(AjoutModifDriverType::class, $administrateur);
        $formDriver->handleRequest($request);

        if ($formDriver->isSubmitted() && $formDriver->isValid()) {
            $administrateur->setRoles(['ROLE_DRIVER']);
           
            $hashedPassword = $hasher->hashPassword(
                $administrateur,
                $administrateur->getPassword()
            );
            $administrateur->setPassword($hashedPassword);
            $entityManager->persist($administrateur);
            // $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash('admin_create_driver', 'Conducteur ajouter avec succès');
            return $this->redirectToRoute('app_administrateur_index', [], Response::HTTP_SEE_OTHER);
        }

    // ajouter bus
        $bu = new Bus();
        $form = $this->createForm(BusType::class, $bu);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bu);
            $entityManager->flush();
            $this->addFlash('admin_create_bus', 'Bus ajouter avec succès');
            return $this->redirectToRoute('app_administrateur_index', [], Response::HTTP_SEE_OTHER);
        }


    // ajouter un point 
        $point = new Point();
        $formPoint = $this->createForm(PointType::class, $point);
        $formPoint->handleRequest($request);

        if ($formPoint->isSubmitted() && $formPoint->isValid()) {
            $entityManager->persist($point);
            $entityManager->flush();
            $this->addFlash('admin_create_point', 'Point ajouter avec succès');
        }


    // ajouter un trajet

        $trajet = new Trajets();
        $formTrajet = $this->createForm(TrajetsType::class, $trajet);
        $formTrajet->handleRequest($request);
        $trajets = $trajetsRepository->findAll();


        if ($formTrajet->isSubmitted() && $formTrajet->isValid()) {
            $entityManager->persist($trajet);
            $entityManager->flush();
            // 2️⃣ Récupérer les points sélectionnés
            $depart = $formTrajet->get('depart')->getData();
            $arrivee = $formTrajet->get('arrivee')->getData();

            // 3️⃣ Mettre le trajet dans les points
            $depart->setTrajet($trajet);
            $arrivee->setTrajet($trajet);

            $entityManager->persist($depart);
            $entityManager->persist($arrivee);
            $entityManager->flush();
            $this->addFlash('admin_create_trajet', 'Trajet créé avec succès !');
        }
        return $this->render('administrateur/index.html.twig', [
            'form' => $form,
            'formDriver' => $formDriver,
            'formPoint'=>$formPoint,
            'formTrajet'=>$formTrajet,

           
        ]);
}
}