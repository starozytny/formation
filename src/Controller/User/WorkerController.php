<?php

namespace App\Controller\User;

use App\Entity\Formation\FoWorker;
use App\Repository\Formation\FoWorkerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/espace-membre/equipe', name: 'user_workers_')]
class WorkerController extends AbstractController
{
    #[Route('/', name: 'index', options: ['expose' => true])]
    public function index(FoWorkerRepository $foWorkerRepository): Response
    {
        return $this->render('user/pages/workers/index.html.twig', [
            'data' => $foWorkerRepository->findBy([], ['lastname' => 'ASC'])
        ]);
    }

    #[Route('/membre/ajouter', name: 'create')]
    public function create(): Response
    {
        return $this->render('user/pages/workers/create.html.twig');
    }

    #[Route('/membre/modifier/{id}', name: 'update', options: ['expose' => true])]
    public function update(FoWorker $elem, SerializerInterface $serializer): Response
    {
        $obj  = $serializer->serialize($elem, 'json', ['groups' => FoWorker::FORM]);
        return $this->render('user/pages/workers/update.html.twig', [
            'elem' => $elem,
            'obj' => $obj,
        ]);
    }
}
