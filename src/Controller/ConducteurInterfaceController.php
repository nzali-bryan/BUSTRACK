<?php
namespace App\Controller;

use App\Entity\Administrateur;
use App\Entity\Bus;
use App\Form\DirectionBusType;
use App\Form\StatusBusType;
// use App\Repository\ConducteursRepository;
use App\Repository\PointRepository;
use App\Repository\BusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BusType;
use App\Repository\AdministrateurRepository;
use App\Form\ProfilType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ConducteurInterfaceController extends AbstractController
{
    #[IsGranted ('ROLE_DRIVER')]
    #[Route('/interface/conducteur/', name: 'app_interface_conducteur')]
    public function index(Request $request, AdministrateurRepository $administrateurRepository, EntityManagerInterface $em,PointRepository $pointRepository): Response
    {
        
    
    $user=$this->getUser();

    $id= $user->getId();
    $UserDriver= $administrateurRepository->find($id);
    $bus=$UserDriver->getBus();
    $trajet=$bus->getTrajet();

    $pointASC = $pointRepository->findByTrajetIdASC($trajet->getId());
    $pointDESC=$pointRepository->findByTrajetIdDESC($trajet->getId());
    

    $form=$this->createForm(StatusBusType::class, $bus);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($form);
        $em->flush();
       

    }
    $formDirection=$this->createForm(DirectionBusType::class, $bus);
    $formDirection->handleRequest($request);
    if ($formDirection->isSubmitted() && $formDirection->isValid()) {

        $em->persist($formDirection);
        $em->flush();
       
    }


    // dd($vitesse,$distance,$tempsInitial);
    {
        return $this->render('interface_conducteur/index.html.twig', [
            'conducteur'=> $UserDriver,
            'bus'=> $bus,
            'trajet'=>$trajet,
            'pointASC'=>$pointASC,
            'pointDESC'=>$pointDESC,
            'form'=> $form->createView(),
            'formDirection'=> $formDirection->createView(),

                ]);
    }

}


#[Route('/bus/{id}/update-direction', name: 'update_direction_bus', methods: ['POST'])]
public function updateDirection(Request $request, Bus $bus,AdministrateurRepository $administrateurRepository, EntityManagerInterface $entityManager): Response
{
    // Vérifie le token CSRF
    $submittedToken = $request->request->get('_token');
    if (!$this->isCsrfTokenValid('update_direction' . $bus->getId(), $submittedToken)) {
        throw $this->createAccessDeniedException('Token invalide');
    }

        // configuration de l'heure
        $user=$this->getUser();
        $id= $user->getId();
        $UserDriver= $administrateurRepository->find($id);
        
        $bus=$UserDriver->getBus();
        $trajet=$bus->getTrajet();
        //  je recupère d'abord l'ancien etat
        $ancienEtat=$bus->getEtat();
        // 
    $direction = $request->request->get('direction');
    $statut=$request->get('statut');
        
    if ($direction ) {
        $bus->setDirection($direction);
        $entityManager->flush();
    }
    if($statut){
        $bus->setEtat($statut); 
        $entityManager->Flush();
    }


        dump($ancienEtat);
        $etatBus=$bus->getEtat();
        if ($etatBus === 'En route' && $ancienEtat !== 'En route')
        {         
            $vitesse=$bus->getVitesse();
            $distance=$trajet->getDistance();
            $tempsInitial=($distance / $vitesse)*60;
            $bus->setTempsInitial((int) round($tempsInitial));
            $bus->setstartTime(new \DateTimeImmutable());
            $entityManager->persist($bus);
            $entityManager->flush();
        }
        elseif ($etatBus === 'Incident' && $ancienEtat !== 'En route'){
            $tempsInitial=$bus->getTempsRestant();
            $bus->setTempsInitial((int) round($tempsInitial));
            $entityManager->persist($bus);
            $entityManager->flush();
        }elseif ($etatBus === 'En route' && $ancienEtat !== 'Incident'){
            $bus->setstartTime(new \DateTimeImmutable());
            $entityManager->persist($bus);
            $entityManager->flush();
        }
return $this->redirectToRoute('app_interface_conducteur', [
    'id' => $bus->getAdministrateur()->getId()
]);
}

#[IsGranted('ROLE_DRIVER')]
#[Route ( '/profil' , name: 'app_conducteur_profil')]
public function profil(Request $request, EntityManagerInterface $em,AuthenticationUtils $authenticationUtils, AdministrateurRepository $administrateurRepository, UserPasswordHasherInterface $hasher):Response
{
    $user= $this->getUser();
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }
    
    $form = $this->createForm(ProfilType::class, $user);
    $form->handleRequest($request);
    $error = $authenticationUtils->getLastAuthenticationError();

    if ($form->isSubmitted() && $form->isValid()) {

        $currentPassword = $form->get('currentPassword')->getData();
        $newPassword = $form->get('newPassword')->getData();

        
        if ($newPassword) {

            
            if (!$hasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('error_password', 'Erreur ancien mot de passe incorrect');
                return $this->redirectToRoute('app_conducteur_profil');
            }

          
            $hashed = $hasher->hashPassword($user, $newPassword);
            $user->setPassword($hashed);
        }

        $em->flush();
        $this->addFlash('success_edit', 'Profil modifier avec succès');
        

        return $this->redirectToRoute('app_conducteur_profil');
    }
    return $this->render('interface_conducteur/profil.html.twig' , [
        'user'=>$user,
        'form' => $form->createView(),
        'error'=>$error,
    ]);

}



}

