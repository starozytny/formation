<?php

namespace App\Controller\Manager;

use App\Entity\Formation\FoFormation;
use App\Entity\Formation\FoTax;
use App\Repository\Formation\FoTaxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/espace-manager/formations', name: 'manager_formations_')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'index', options: ['expose' => true])]
    public function index(Request $request): Response
    {
        return $this->render('manager/pages/formations/index.html.twig', ['highlight' => $request->query->get('h')]);
    }

    #[Route('/formation/ajouter', name: 'create')]
    public function create(SerializerInterface $serializer, FoTaxRepository $taxRepository): Response
    {
        $taxs = $taxRepository->findBy([], ['taux' => 'ASC']);
        $taxs = $serializer->serialize($taxs, 'json', ['groups' => FoTax::SELECT]);

        return $this->render('manager/pages/formations/create.html.twig', [
            'taxs' => $taxs
        ]);
    }

    #[Route('/formation/modifier/{id}', name: 'update', options: ['expose' => true])]
    public function update(FoFormation $elem, SerializerInterface $serializer, FoTaxRepository $taxRepository): Response
    {
        $taxs = $taxRepository->findBy([], ['taux' => 'ASC']);
        $taxs = $serializer->serialize($taxs, 'json', ['groups' => FoTax::SELECT]);
        $obj  = $serializer->serialize($elem, 'json', ['groups' => FoFormation::FORM]);
        return $this->render('manager/pages/formations/update.html.twig', [
            'elem' => $elem,
            'obj' => $obj,
            'taxs' => $taxs
        ]);
    }
}
