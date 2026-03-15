<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InterfaceConducteurController extends AbstractController
{
    #[Route('/interface/conducteur', name: 'app_interface_conducteur')]
    public function index(): Response
    {
        return $this->render('interface_conducteur/index.html.twig', [
            'controller_name' => 'InterfaceConducteurController',
        ]);
    }
}
