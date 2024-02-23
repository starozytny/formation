<?php

namespace App\Controller\InternApi\Formation\Settings;

use App\Entity\Formation\FoTax;
use App\Repository\Formation\FoTaxRepository;
use App\Service\ApiResponse;
use App\Service\Data\DataFormation;
use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/intern/api/fo/settings/taxs', name: 'intern_api_fo_settings_taxs_')]
class TaxController extends AbstractController
{
    #[Route('/list', name: 'list', options: ['expose' => true], methods: 'GET')]
    public function list(FoTaxRepository $repository, ApiResponse $apiResponse): Response
    {
        return $apiResponse->apiJsonResponse($repository->findAll(), FoTax::LIST);
    }

    public function submitForm($type, FoTaxRepository $repository, FoTax $obj, Request $request, ApiResponse $apiResponse,
                               ValidatorService $validator, DataFormation $dataEntity): JsonResponse
    {
        $data = json_decode($request->getContent());
        if ($data === null) {
            return $apiResponse->apiJsonResponseBadRequest('Les données sont vides.');
        }

        $obj = $dataEntity->setDataTax($obj, $data);

        if($existe = $repository->findOneBy(['code' => $obj->getCode()])){
            if($type == "create" || ($type == "update" && $existe->getId() != $obj->getId())){
                return $apiResponse->apiJsonResponseValidationFailed([
                    ["name" => "code", "message" => "Ce code existe déjà."]
                ]);
            }
        }
        if($existe = $repository->findOneBy(['taux' => $obj->getTaux()])){
            if($type == "create" || ($type == "update" && $existe->getId() != $obj->getId())){
                return $apiResponse->apiJsonResponseValidationFailed([
                    ["name" => "taux", "message" => "Ce taux existe déjà."]
                ]);
            }
        }

        $noErrors = $validator->validate($obj);
        if ($noErrors !== true) {
            return $apiResponse->apiJsonResponseValidationFailed($noErrors);
        }

        $repository->save($obj, true);
        return $apiResponse->apiJsonResponse($obj, FoTax::LIST);
    }

    #[Route('/create', name: 'create', options: ['expose' => true], methods: 'POST')]
    #[IsGranted('ROLE_MANAGER')]
    public function create(Request $request, ApiResponse $apiResponse, ValidatorService $validator,
                           DataFormation $dataEntity, FoTaxRepository $repository): Response
    {
        return $this->submitForm("create", $repository, new FoTax(), $request, $apiResponse, $validator, $dataEntity);
    }

    #[Route('/update/{id}', name: 'update', options: ['expose' => true], methods: 'PUT')]
    #[IsGranted('ROLE_MANAGER')]
    public function update(Request $request, FoTax $obj, ApiResponse $apiResponse, ValidatorService $validator,
                           DataFormation $dataEntity, FoTaxRepository $repository): Response
    {
        return $this->submitForm("update", $repository, $obj, $request, $apiResponse, $validator, $dataEntity);
    }

    #[Route('/delete/{id}', name: 'delete', options: ['expose' => true], methods: 'DELETE')]
    #[IsGranted('ROLE_MANAGER')]
    public function delete(FoTax $obj, FoTaxRepository $repository, ApiResponse $apiResponse): Response
    {
        $repository->remove($obj, true);

        return $apiResponse->apiJsonResponseSuccessful("ok");
    }
}
