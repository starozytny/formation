<?php

namespace App\Controller\Manager\Settings;

use App\Entity\Formation\FoTax;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/espace-manager/settings/taxes', name: 'manager_settings_taxs_')]
class TaxController extends AbstractController
{
    #[Route('/taxe/ajouter', name: 'create', options: ['expose' => true])]
    public function create(): Response
    {
        return $this->render('manager/pages/settings/taxs/create.html.twig');
    }

    #[Route('/taxe/modifier/{id}', name: 'update', options: ['expose' => true])]
    public function update(FoTax $elem, SerializerInterface $serializer): Response
    {
        $obj  = $serializer->serialize($elem, 'json', ['groups' => FoTax::FORM]);
        return $this->render('manager/pages/settings/taxs/update.html.twig', ['elem' => $elem, 'obj' => $obj]);
    }
}
