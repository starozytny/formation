<?php

namespace App\Controller\Api\Bill;

use App\Entity\Bill\BiCustomer;
use App\Entity\Bill\BiSociety;
use App\Service\ApiResponse;
use App\Service\Data\Bill\DataBill;
use App\Service\Data\DataService;
use App\Service\ValidatorService;
use Doctrine\Common\Persistence\ManagerRegistry;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/bill/customers", name="api_bill_customers_")
 */
class CustomerController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function submitForm($type, BiCustomer $obj, Request $request, ApiResponse $apiResponse,
                               ValidatorService $validator, DataBill $dataEntity): JsonResponse
    {
        $em = $this->doctrine->getManager();
        $data = json_decode($request->getContent());

        if ($data === null) {
            return $apiResponse->apiJsonResponseBadRequest('Les données sont vides.');
        }

        $society = $em->getRepository(BiSociety::class)->find($data->societyId);
        if(!$society){
            return $apiResponse->apiJsonResponseBadRequest('La société est introuvable, veuillez contacter le support.');
        }

        $obj = $dataEntity->setDataCustomer($obj, $data, $society);
        if($type === "create" && $data->useNumero[0] == 1){
            $obj->setNumero($dataEntity->createNumero("customer", new \DateTime(), $society));
        }else{
            $numero = $data->numero ? trim($data->numero) : "";
            $existe = $em->getRepository(BiCustomer::class)->findOneBy(['numero' => $numero]);
            if($existe && $existe->getId() != $obj->getId()){
                return $apiResponse->apiJsonResponseValidationFailed([[
                    'name' => "numero",
                    'message' => "Ce numéro est déjà attribué."
                ]]);
            }
            $obj->setNumero($numero);
        }

        $noErrors = $validator->validate($obj);
        if ($noErrors !== true) {
            return $apiResponse->apiJsonResponseValidationFailed($noErrors);
        }

        $em->persist($obj);
        $em->flush();

        return $apiResponse->apiJsonResponse($obj, BiCustomer::CUSTOMER_READ);
    }

    /**
     * @Route("/", name="create", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a new object"
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="JSON empty or missing data or validation failed",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param Request $request
     * @param ValidatorService $validator
     * @param ApiResponse $apiResponse
     * @param DataBill $dataEntity
     * @return JsonResponse
     */
    public function create(Request $request, ValidatorService $validator, ApiResponse $apiResponse, DataBill $dataEntity): JsonResponse
    {
        return $this->submitForm("create", new BiCustomer(), $request, $apiResponse, $validator, $dataEntity);
    }

    /**
     * @Route("/{id}", name="update", options={"expose"=true}, methods={"PUT"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns an object"
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or user",
     * )
     * @OA\Response(
     *     response=400,
     *     description="Validation failed",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param Request $request
     * @param $id
     * @param ValidatorService $validator
     * @param ApiResponse $apiResponse
     * @param DataBill $dataEntity
     * @return JsonResponse
     */
    public function update(Request $request, $id, ValidatorService $validator,  ApiResponse $apiResponse, DataBill $dataEntity): JsonResponse
    {
        $em = $this->doctrine->getManager();

        $obj = $em->getRepository(BiCustomer::class)->find($id);
        return $this->submitForm("update", $obj, $request, $apiResponse, $validator, $dataEntity);
    }

    /**
     * @Route("/{id}", name="delete", options={"expose"=true}, methods={"DELETE"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param $id
     * @param DataService $dataService
     * @return JsonResponse
     */
    public function delete($id, DataService $dataService): JsonResponse
    {
        $em = $this->doctrine->getManager();

        $obj = $em->getRepository(BiCustomer::class)->find($id);
        return $dataService->delete($this->getUser(), $obj);
    }

    /**
     * @Route("/", name="delete_group", options={"expose"=true}, methods={"DELETE"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param Request $request
     * @param DataService $dataService
     * @return JsonResponse
     */
    public function deleteSelected(Request $request, DataService $dataService): JsonResponse
    {
        return $dataService->deleteSelected($this->getUser(), BiCustomer::class, json_decode($request->getContent()));
    }
}
