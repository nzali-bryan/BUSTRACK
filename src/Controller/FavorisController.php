<?php

namespace App\Controller;

use App\Entity\Favori;
use App\Entity\Favoris;
use App\Entity\Trajets;
use App\Repository\BusRepository;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class FavorisController extends AbstractController
{
    #[Route('/favori', name: 'app_favoris')]
    public function index(): Response
    {
        return $this->render('favoris/index.html.twig', [
            'controller_name' => 'FavorisController',
        ]);
    }
       #[Route('/favoris', name: 'favoris_list')]
       public function Listfavoris( FavorisRepository $favoriRepository, BusRepository $busRepository):Response
       {

       $user= $this->getUser();
        $favoris = $favoriRepository->findFavoris($user->getId());
         $bus = $busRepository->findBusByUserFavoris($user->getId());
         dump($bus);
        return $this->render('user/favoris.html.twig', [
            'favoris' => $favoris,
            'AllBus' => $bus,
        ]);

       }



        #[Route('/favori/ajouter/{id}', name: 'favori_ajouter')]
    #[IsGranted('ROLE_USER')] // uniquement pour les utilisateurs connectés
    public function ajouter(Trajets $trajet, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Vérifier si le trajet est déjà favori
        foreach ($user->getFavoris() as $favori) {
            if ($favori->getTrajets() === $trajet) {
                $this->addFlash('info', 'Ce trajet est déjà dans vos favoris.');
                return $this->redirectToRoute('app_user'); // ou la page actuelle
            }
        }

        $favori = new Favoris();
        $favori->setAdministrateur($user);
        $favori->setTrajets($trajet);

        $em->persist($favori);
        $em->flush();

        $this->addFlash('success', 'Trajet ajouté à vos favoris !');

        return $this->redirectToRoute('app_user'); // ou page actuelle
    }
}
