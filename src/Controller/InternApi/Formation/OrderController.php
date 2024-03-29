<?php

namespace App\Controller\InternApi\Formation;

use App\Entity\Enum\Formation\OrderStatusType;
use App\Entity\Formation\FoOrder;
use App\Entity\Formation\FoParticipant;
use App\Entity\Main\User;
use App\Repository\Formation\FoFormationRepository;
use App\Repository\Formation\FoOrderRepository;
use App\Repository\Formation\FoParticipantRepository;
use App\Repository\Formation\FoWorkerRepository;
use App\Service\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/intern/api/fo/orders', name: 'intern_api_fo_orders_')]
class OrderController extends AbstractController
{
    #[Route('/list', name: 'list', options: ['expose' => true], methods: 'GET')]
    public function list(FoOrderRepository $repository, FoParticipantRepository $participantRepository,
                         SerializerInterface $serializer, ApiResponse $apiResponse): Response
    {
        $orders = $repository->findBy(['user' => $this->getUser()]);
        $participants = $participantRepository->findBy(['foOrder' => $orders]);

        $orders = $serializer->serialize($orders, 'json', ['groups' => FoOrder::LIST]);
        $participants = $serializer->serialize($participants, 'json', ['groups' => FoParticipant::LIST]);

        return $apiResponse->apiJsonResponseCustom([
            'orders' => $orders,
            'participants' => $participants
        ]);
    }

    #[Route('/create', name: 'create', options: ['expose' => true], methods: 'POST')]
    public function create(Request $request, ApiResponse $apiResponse, ValidatorService $validator,
                           FoOrderRepository $repository, FoFormationRepository $formationRepository,
                           FoWorkerRepository $workerRepository, FoParticipantRepository $participantRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent());
        if ($data === null) {
            return $apiResponse->apiJsonResponseBadRequest('Les données sont vides.');
        }

        $formation = $formationRepository->findOneBy(['id' => $data->formationId]);
        if(!$formation || !$formation->isIsOnline()){
            return $apiResponse->apiJsonResponseBadRequest("La formation n'existe pas ou n'est pas en ligne.");
        }

        $workersId = [];
        foreach($data->participants as $p){
            $workersId[] = $p->id;
        }
        $workers = $workerRepository->findBy(['id' => $workersId]);

        if($formation->getNbRemain() < count($workers)){
            return $apiResponse->apiJsonResponseBadRequest("La formation n'a plus que " . $formation->getNbRemain() . " places disponibles.");
        }

        $formation->setNbRemain($formation->getNbRemain() - count($workers));

        $obj = (new FoOrder())
            ->setUser($user)
            ->setFormation($formation)
            ->setStatus(OrderStatusType::Creation)
        ;

        foreach($workers as $worker){
            $participant = (new FoParticipant())
                ->setWorker($worker)
                ->setFoOrder($obj)
            ;

            $participantRepository->save($participant);
        }

        $noErrors = $validator->validate($obj);
        if ($noErrors !== true) {
            return $apiResponse->apiJsonResponseValidationFailed($noErrors);
        }

        $repository->save($obj, true);
        return $apiResponse->apiJsonResponse($obj, FoOrder::ID);
    }
}
