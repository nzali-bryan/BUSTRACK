<?php

namespace App\Controller;

use App\Entity\Bus;
use App\Repository\BusRepository;
use App\Repository\AdministrateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Administrateur;
use App\Form\NewUserType;
use App\Repository\TrajetsRepository;
use  Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/interface/passager', name: '')]
final class UserController extends AbstractController
{
    #[Route('', name: 'app_user')]
    public function index(BusRepository $busRepository, TrajetsRepository $trajetsRepository, Request $request, AdministrateurRepository $administrateurRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        $countbus = $busRepository->findBusActifs([
            'En Route',
            'chargement',
            'Incident',
            'Chargement'

        ]);

        $zone1 = $request->query->get('zone');



        // creer un utilisateur
        // creer un utilisateur
        // creer un utilisateur

        $administrateur = new Administrateur();
        $form = $this->createForm(NewUserType::class, $administrateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $hasher->hashPassword(
                $administrateur,
                $administrateur->getPassword()
            );
            $administrateur->setPassword($hashedPassword);
            $entityManager->persist($administrateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        // filtre très personnalise 
        $zone = $request->query->get('zonefilter');
        $trajet = $request->query->get('trajet');
        $statut = $request->query->get('statut');
        $zonesAutorisees = ['Douala I', 'Douala II', 'Douala III', 'Douala IV', 'Douala V'];
        if ($zone && !in_array($zone, $zonesAutorisees)) {
            $zone = null;
        }
        if ($trajet && !is_numeric($trajet)) {
            $trajet = null;
        }
        $etatsAutorisees = ['En route', 'Chargement', 'Incident', 'Inactif'];
        if ($statut && !in_array($statut, $etatsAutorisees)) {
            $statut = null;
        }
        $busFilter = $busRepository->findfilter($zone, $trajet, $statut);
        // dd($busFilter);


        // filtre très personalisée


        // filtre par mot 
        $mot = $request->query->get('mot');
        // filtre par mot 
        if ($mot) {
            $Allbus = $busRepository->searchBus($mot);
        } elseif ($trajet || $statut || $zone) {
            $Allbus = $busRepository->findfilter($zone, $trajet, $statut);
        } elseif ($zone1) {
            $Allbus = $busRepository->findZoneBus($zone1);
        } else {
            $Allbus = $busRepository->findAll();
        }

        return $this->render('user/index.html.twig', [
            'formCreateUser' => $form,
            'NombreDeBus' => $countbus,
            'AllBus' => $Allbus,
            'conducteurs' => $administrateurRepository->findDriver(),
            'trajets' => $trajetsRepository->findAll(),
        ]);
    }


    #[Route('/creer', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        $administrateur = new Administrateur();
        $form = $this->createForm(NewUserType::class, $administrateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $hasher->hashPassword(
                $administrateur,
                $administrateur->getPassword()
            );
            $administrateur->setPassword($hashedPassword);
            $entityManager->persist($administrateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'form' => $form,
            'formCreateUser' => $form,
        ]);
    }

    #[Route('/profil', name: 'app_user_profil_NoConnect')]
    public function profilNoConnect(Request $request)
    {
        return $this->render('user/profilNoConnect.html.twig',[

        ]);
    }

    #[Route('/favoris', name: 'app_user_favoris_NoConnect')]
    public function favorisNoConnect(Request $request)
    {
        return $this->render('user/favorisNoConnect.html.twig',[

        ]);
    }

    #[Route('/profil/{id}', name:'app_user_profil')]
    public function profil(Request $request, $id,AdministrateurRepository $administrateurRepository)

    {
        $infoUser=$administrateurRepository->find($id);
        
        return $this->render('user/profil.html.twig',[
            'users'=>$infoUser,

        ]);
    }
    // #[Route('/filter', name: 'app_user_filter', methods: ['GET', 'POST'])]
    // public function filter(Request $request, EntityManagerInterface $entityManager, BusRepository $busRepository)
    // {
    //     $zone = $request->query->get('zonefilter');
    //     $trajet = $request->query->get('trajet');
    //     $statut = $request->query->get('statut');
    //     $zonesAutorisees = ['Douala I', 'Douala II', 'Douala III', 'Douala IV', 'Douala V'];
    //     if ($zone && !in_array($zone, $zonesAutorisees)) {
    //         $zone = null;
    //     }
    //     if ($trajet && !is_numeric($trajet)) {
    //         $trajet = null;
    //     }
    //     $etatsAutorisees = ['En route', 'Chargement', 'Incident', 'Inactif'];
    //     if ($statut && !in_array($statut, $etatsAutorisees)) {
    //         $statut = null;
    //     }
    //     $busFilter = $busRepository->findfilter($zone, $trajet, $statut);
    //     dd($busFilter);
    //     return $this->render('user/index.html.twig'[]);
    // }

    
}
