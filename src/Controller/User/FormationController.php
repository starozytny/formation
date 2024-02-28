<?php

namespace App\Controller\User;

use App\Entity\Formation\FoFormation;
use App\Entity\Formation\FoParticipant;
use App\Entity\Formation\FoWorker;
use App\Repository\Formation\FoFormationRepository;
use App\Repository\Formation\FoParticipantRepository;
use App\Repository\Formation\FoWorkerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/espace-membre/formations', name: 'user_formations_')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(FoFormationRepository $foFormationRepository): Response
    {
        return $this->render('user/pages/formations/index.html.twig', [
            'data' => $foFormationRepository->findBy(['isOnline' => true], ['startAt' => 'ASC'])
        ]);
    }

    #[Route('/formation/{slug}', name: 'read')]
    public function read(FoFormation $obj): Response
    {
        if(!$obj->isIsOnline()){
            $this->addFlash("error", "Préinscription fermée.");
            return $this->redirectToRoute('user_formations_index');
        }

        return $this->render('user/pages/formations/read.html.twig', [
            'elem' => $obj
        ]);
    }

    #[Route('/formation/{slug}/preinscription', name: 'preregistration')]
    public function preregistration(FoFormation $obj, SerializerInterface $serializer,
                                    FoWorkerRepository $workerRepository,
                                    FoParticipantRepository $participantRepository): Response
    {
        if(!$obj->isIsOnline() || $obj->getNbRemain() <= 0){
            $this->addFlash("error", "Préinscription fermée.");
            return $this->redirectToRoute('user_formations_read', ['slug' => $obj->getSlug()]);
        }

        $workers = $workerRepository->findBy(['isTrash' => false], ['lastname' => 'ASC']);
        $tmpParticipants = $participantRepository->findBy(['worker' => $workers]);

        $participants = [];
        foreach($tmpParticipants as $participant){
            if($participant->getFoOrder()->getFormation()->getId() == $obj->getId()){
                $participants[] = $participant;
            }
        }

        return $this->render('user/pages/formations/preregistration.html.twig', [
            'elem' => $obj,
            'formation' => $serializer->serialize($obj, 'json', ['groups' => FoFormation::PREREGISTRATION]),
            'workers' => $serializer->serialize($workers, 'json', ['groups' => FoWorker::SELECT]),
            'participants' => $serializer->serialize($participants, 'json', ['groups' => FoParticipant::REGISTERED]),
        ]);
    }
}
