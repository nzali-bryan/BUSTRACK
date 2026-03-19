<?php

namespace App\Controller;

use App\Entity\Point;
use App\Form\PointType;
use App\Repository\PointRepository;
use App\Repository\TrajetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/point')]
final class PointController extends AbstractController
{
    #[Route(name: 'app_point_index',  methods: ['GET', 'POST'])]
    public function index(Request $request,PointRepository $pointRepository,TrajetsRepository $trajetsRepository, EntityManagerInterface $entityManager): Response
    {

        $point = new Point();
        $form = $this->createForm(PointType::class, $point);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($point);
            $entityManager->flush();

            return $this->redirectToRoute('app_point_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('point/index.html.twig', [
            'trajet'=>$trajetsRepository->findAll(),
            'points' => $pointRepository->findBy([],['nom'=>'ASC']),
            'form' => $form,
        ]);
    }

    // #[Route('/new', name: 'app_point_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $point = new Point();
    //     $form = $this->createForm(PointType::class, $point);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($point);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_point_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('point/index.html.twig', [
    //         'point1' => $point,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_point_show', methods: ['GET'])]
    // public function show(Point $point): Response
    // {
    //     return $this->render('point/show.html.twig', [
    //         'point' => $point,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_point_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Point $point, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PointType::class, $point);
        $form->handleRequest($request);
        $data = $request->request->all()['points'];

        $point->setNom($data['nom']);
       
        $entityManager->flush();
        return $this->redirectToRoute('app_point_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_point_delete', methods: ['POST'])]
    public function delete(Request $request, Point $point, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$point->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($point);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_point_index', [], Response::HTTP_SEE_OTHER);
    }
}
