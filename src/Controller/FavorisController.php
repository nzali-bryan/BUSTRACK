<?php

namespace App\Controller;

use App\Entity\Favori;
use App\Entity\Favoris;
use App\Entity\Trajets;
use App\Entity\Administrateur;
use App\Form\NewUserType;
use App\Repository\AdministrateurRepository;
use App\Repository\BusRepository;
use App\Repository\FavorisRepository;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class FavorisController extends AbstractController
{
    #[Route('/favori', name: 'app_favoris')]
    public function index(Request $request,AuthenticationUtils $authenticationUtils,  AdministrateurRepository $administrateurRepository, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        // $administrateur = new Administrateur();
        // $form = $this->createForm(NewUserType::class, $administrateur);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $hashedPassword = $hasher->hashPassword(
        //         $administrateur,
        //         $administrateur->getPassword()
        //     );
        //     $administrateur->setPassword($hashedPassword);
        //     $em->persist($administrateur);
        //     $em->flush();

        //     return $this->redirectToRoute('app_favoris', [], Response::HTTP_SEE_OTHER);
        // }
        return $this->render('user/favorisNoConnect.html.twig', [
            // 'form' => $form,
            // 'formCreateUser' => $form,
        ]);
    }
    #[Route('/favoris', name: 'favoris_list')]
    public function Listfavoris(FavorisRepository $favorisRepository, BusRepository $busRepository): Response
    {



        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_favoris');
        }
        $id= $user->getId();
        $favoris = $favorisRepository->findFavoris($id);
        $bus = $busRepository->findBusByUserFavoris($id);
        
         $countfavoris=$favorisRepository->count([
            'administrateur'=>$user,
         ]);
        //  dump($favoris);
        return $this->render('user/favoris.html.twig', [
            'favoris' => $favoris,
            'AllBus' => $bus,
            'countfavoris'=>$countfavoris,
        ]);
        
    }

    

    #[Route('/favori/ajouter/{id}', name: 'favori_ajouter')]
    #[IsGranted('ROLE_USER')] 
    public function ajouter(Trajets $trajet, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        
        // Vérifier si le trajet est déjà favori
        foreach ($user->getFavoris() as $favori) {
            if ($favori->getTrajets() === $trajet) {
                $this->addFlash('info_favoris', 'Ce trajet est déjà dans vos favoris.');
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
         $this->addFlash('delete_favoris', 'Ce trajet à été supprimé de vos favoris !');

        return $this->redirectToRoute('favoris_list');

    }
    
    #[Route('/favoris/delete/', name:'favoris_delete_All')]
    public function deleteAllFavoris( FavorisRepository $favorisRepository, EntityManagerInterface $em): Response
    {
        $user= $this-> getUser();
        $id= $user->getId();
 
        if(!$user){
            return $this->redirectToRoute('app_login');
        }


        $Allfavoris = $favorisRepository->findFavoris($id);
        if(!$Allfavoris){
            $this->addFlash('Delete_Allfavoris', 'aucun trajet à supprimé !');
             return $this->redirectToRoute('favoris_list');
        }
        foreach($Allfavoris as $favoris){

            $em->remove($favoris);
            $em->flush();
        }
        $this->addFlash('delete_Allfavoris', 'Tous les trajets on été supprimés !');


        return $this->redirectToRoute('favoris_list');

    }
}
