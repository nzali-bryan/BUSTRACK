<?php

namespace App\Controller;

use App\Entity\Favori;
use App\Entity\Favoris;
use App\Entity\Trajets;
use App\Repository\BusRepository;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class FavorisController extends AbstractController
{
    #[Route('/favori', name: 'app_favoris')]
    public function index(): Response
    {
        return $this->render('user/favorisNoConnect.html.twig', [
         
        ]);
    }
    #[Route('/favoris', name: 'favoris_list')]
    public function Listfavoris(FavorisRepository $favorisRepository, BusRepository $busRepository): Response
    {



        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_favoris');
        }
        $favoris = $favorisRepository->findFavoris($user->getId());
        $bus = $busRepository->findBusByUserFavoris($user->getId());
        
         $countfavoris=$favorisRepository->count([
            'administrateur'=>$user,
         ]);
         dump($favoris);
        return $this->render('user/favoris.html.twig', [
            'favoris' => $favoris,
            'AllBus' => $bus,
            'countfavoris'=>$countfavoris,
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

    #[Route('/favoris/delete/{id}', name:'favoris_delete')]
    public function deleteFavoris( int $id,FavorisRepository $favorisRepository, EntityManagerInterface $em): Response
    {
        $user= $this-> getUser();
 
        if(!$user){
            return $this->redirectToRoute('app_login');
        }


       
        $favoris=$favorisRepository->findOneBy([
            'administrateur'=>$user,
            'trajets'=>$id
        ]);
        $em->remove($favoris);
        $em->flush();

        return $this->redirectToRoute('favoris_list');

    }
    
    #[Route('/favoris/delete/', name:'favoris_delete_All')]
    public function deleteAllFavoris( FavorisRepository $favorisRepository, EntityManagerInterface $em): Response
    {
        $user= $this-> getUser();
        $id= $this->$user->getId();
 
        if(!$user){
            return $this->redirectToRoute('app_login');
        }


        $Allfavoris = $favorisRepository->findFavoris($user->getId());
        foreach($Allfavoris as $favoris){
            $em->remove($favoris);
            $em->flush();
        }


        return $this->redirectToRoute('favoris_list');

    }
}
