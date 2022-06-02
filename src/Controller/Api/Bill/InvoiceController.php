<?php

namespace App\Controller\Api\Bill;

use App\Entity\User;
use App\Entity\Bill\BiHistory;
use App\Entity\Bill\BiInvoice;
use App\Entity\Bill\BiProduct;
use App\Entity\Bill\BiQuotation;
use App\Entity\Bill\BiSociety;
use App\Service\ApiResponse;
use App\Service\Bill\BillService;
use App\Service\Data\Bill\DataBill;
use App\Service\MailerService;
use App\Service\SanitizeData;
use App\Service\ValidatorService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use Mpdf\MpdfException;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @Route("/api/bill/invoices", name="api_bill_invoices_")
 */
class InvoiceController extends AbstractController
{
    private $billService;
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, BillService $billService)
    {
        $this->billService = $billService;
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/data/{id}", name="data", options={"expose"=true}, methods={"GET"})
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns data"
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="JSON empty or missing data or validation failed",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param $id
     * @param ApiResponse $apiResponse
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function data($id, ApiResponse $apiResponse, SerializerInterface $serializer): JsonResponse
    {
        $mainSociety = $this->billService->getMainSociety($id);
        $em = $this->doctrine->getManager();

        $society = $this->billService->getSocietyByMainSociety($mainSociety);

        $objs = $em->getRepository(BiInvoice::class)->findBy(['society' => $society]);
        $objs = $serializer->serialize($objs, 'json', ['groups' => BiInvoice::INVOICE_READ]);

        $params = $this->billService->getDataCommonPage($mainSociety->getManager(), $society, BiProduct::TYPE_QUOTATION, $serializer);

        return $apiResponse->apiJsonResponseCustom(array_merge([
            'invoices' => $objs,
        ], $params));
    }

    /**
     * @throws Exception
     */
    public function submitForm($type, BiInvoice $obj, Request $request, ApiResponse $apiResponse,
                               ValidatorService $validator, DataBill $dataEntity, MailerService $mailerService): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->doctrine->getManager();

        $data = json_decode($request->getContent());

        if ($data === null) {
            return $apiResponse->apiJsonResponseBadRequest('Les données sont vides.');
        }

        $toGenerate = (bool)$data->toGenerate;

        $society = $em->getRepository(BiSociety::class)->find($data->societyId);
        if(!$society){
            return $apiResponse->apiJsonResponseBadRequest('La société est introuvable, veuillez contacter le support.');
        }

        //OLD products
        $this->billService->removeProducts($user, $obj);

        //NEW products
        $products = [];
        foreach($data->products as $pr){
            $products[] = $dataEntity->setDataProduct(new BiProduct(), $pr, $society);
        }

        $obj = $dataEntity->setDataInvoice($obj, $data, $society);

        if($type == "update"){
            $obj->setUpdatedAt(new \DateTime());
        }

        $noErrors = $validator->validate($obj);
        if ($noErrors !== true) {
            return $apiResponse->apiJsonResponseValidationFailed($noErrors);
        }

        $em->persist($obj);
        $em->flush();

        if($data->quotationId){
            $quotation = $em->getRepository(BiQuotation::class)->find($data->quotationId);
            ($quotation)
                ->setInvoiceId($obj->getId())
                ->setRefInvoice($obj->getNumero())
                ->setIsArchived(true);
            ;
        }

        $this->billService->updateProductIdentifiant($user, $obj, $products, BiProduct::TYPE_INVOICE);
        $em->flush();

        if($toGenerate){
            $this->billService->generateAndSendInvoice($obj, $data, $user, $dataEntity, $apiResponse, $mailerService);
        }

        return $apiResponse->apiJsonResponse($obj, BiInvoice::INVOICE_READ);
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
     * @param MailerService $mailerService
     * @return JsonResponse
     * @throws Exception
     */
    public function create(Request $request, ValidatorService $validator, ApiResponse $apiResponse,
                           DataBill $dataEntity, MailerService $mailerService): JsonResponse
    {
        return $this->submitForm("create", new BiInvoice(), $request, $apiResponse, $validator, $dataEntity, $mailerService);
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
     * @param MailerService $mailerService
     * @return JsonResponse
     * @throws Exception
     */
    public function update(Request $request, $id, ValidatorService $validator,  ApiResponse $apiResponse,
                           DataBill $dataEntity, MailerService $mailerService): JsonResponse
    {
        $em = $this->doctrine->getManager();

        $obj = $em->getRepository(BiInvoice::class)->find($id);
        if($obj->getStatus() != BiInvoice::STATUS_DRAFT){
            return $apiResponse->apiJsonResponseBadRequest("Vous ne pouvez pas modifier cette facture.");
        }
        return $this->submitForm("update", $obj, $request, $apiResponse, $validator, $dataEntity, $mailerService);
    }

    /**
     * @Route("/delete/{id}", name="delete", options={"expose"=true}, methods={"DELETE"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or user",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param $id
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function delete($id, ApiResponse $apiResponse): JsonResponse
    {
        $em = $this->doctrine->getManager();

        $obj = $em->getRepository(BiInvoice::class)->find($id);
        if($obj->getStatus() !== BiInvoice::STATUS_DRAFT){
            return $apiResponse->apiJsonResponseBadRequest("Vous ne pouvez pas supprimer une facture établie.");
        }

        $products = $em->getRepository(BiProduct::class)->findBy(['identifiant' => $obj->getIdentifiant()]);
        foreach($products as $product){
            $em->remove($product);
        }

        $em->remove($obj);
        $em->flush();

        return $apiResponse->apiJsonResponseSuccessful(true);
    }

    /**
     * @Route("/duplicate/{id}", name="duplicate", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or user",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param $id
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function duplicate($id, ApiResponse $apiResponse): JsonResponse
    {
        $em = $this->doctrine->getManager();

        $obj = $em->getRepository(BiInvoice::class)->find($id);

        $products = $em->getRepository(BiProduct::class)->findBy(['identifiant' => $obj->getIdentifiant()]);

        $createdAt = new \DateTime();
        $createdAt->setTimezone(new \DateTimeZone("Europe/Paris"));

        $newObj = clone $obj;
        $newObj = ($newObj)
            ->setStatus(BiInvoice::STATUS_DRAFT)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt(null)
            ->setUid(Uuid::v4())
            ->setQuotationId(null)
            ->setRefQuotation(null)
            ->setAvoirId(null)
            ->setRefAvoir(null)
            ->setContractId(null)
            ->setRefContract(null)
            ->setRelationId(null)
            ->setRefRelation(null)
        ;
        $em->persist($newObj);
        $em->flush();

        foreach($products as $product){
            $pr = clone $product;
            $pr = ($pr)
                ->setUid(Uuid::v4())
                ->setIdentifiant($newObj->getIdentifiant())
            ;

            $em->persist($pr);
        }

        $em->flush();

        return $apiResponse->apiJsonResponse($newObj, BiInvoice::INVOICE_READ);
    }

    /**
     * @Route("/generate/{id}", name="generate", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or user",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param Request $request
     * @param $id
     * @param ApiResponse $apiResponse
     * @param DataBill $dataEntity
     * @param MailerService $mailerService
     * @return JsonResponse
     * @throws Exception
     */
    public function generate(Request $request, $id, ApiResponse $apiResponse, DataBill $dataEntity, MailerService $mailerService): JsonResponse
    {
        $em = $this->doctrine->getManager();
        $data = json_decode($request->getContent());

        $obj = $em->getRepository(BiInvoice::class)->find($id);

        return $this->billService->generateAndSendInvoice($obj, $data, $this->getUser(), $dataEntity, $apiResponse, $mailerService);
    }

    /**
     * @Route("/archive/{id}", name="archive", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or user",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param $id
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     */
    public function archive($id, ApiResponse $apiResponse): JsonResponse
    {
        $em = $this->doctrine->getManager();

        $obj = $em->getRepository(BiInvoice::class)->find($id);

        $obj->setIsArchived(!$obj->getIsArchived());
        $em->flush();

        return $apiResponse->apiJsonResponse($obj, BiInvoice::INVOICE_READ);
    }

    /**
     * @Route("/envoyer/{id}", name="send", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or user",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param $id
     * @param ApiResponse $apiResponse
     * @param MailerService $mailerService
     * @return JsonResponse
     */
    public function send($id, ApiResponse $apiResponse, MailerService $mailerService): JsonResponse
    {
        $em = $this->doctrine->getManager();

        $obj = $em->getRepository(BiInvoice::class)->find($id);

        $obj->setIsSent(true);
        if(!$mailerService->sendInvoice($obj)){

            $obj->setIsSent(false);
            $em->flush();

            return $apiResponse->apiJsonResponseValidationFailed([[
                'name' => 'message',
                'message' => "Le message n\'a pas pu être délivré. Veuillez contacter le support."
            ]]);
        }

        $em->flush();

        return $apiResponse->apiJsonResponse($obj, BiInvoice::INVOICE_READ);
    }

    /**
     * @Route("/payement/{id}", name="payement", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or user",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param Request $request
     * @param $id
     * @param ApiResponse $apiResponse
     * @param DataBill $dataEntity
     * @param SanitizeData $sanitizeData
     * @return JsonResponse
     * @throws Exception
     */
    public function payement(Request $request, $id, ApiResponse $apiResponse, DataBill $dataEntity, SanitizeData $sanitizeData): JsonResponse
    {
        $em = $this->doctrine->getManager();
        $data = json_decode($request->getContent());

        $obj = $em->getRepository(BiInvoice::class)->find($id);

        $dateAt = $sanitizeData->createDate($data->dateAt);
        $price = $sanitizeData->setToFloat($data->price, 0);
        $name = $data->name ? $sanitizeData->trimData($data->name) : "Paiement du " . $dateAt->format('d/m/Y') . ' de ' . $price;

        $history = $dataEntity->setDataHistory(new BiHistory(), $obj, BiHistory::TYPE_PAYEMENT, $name , $dateAt, $price);

        $remaining = $obj->getToPay() - $price;
        $obj = ($obj)
            ->setToPay($remaining)
            ->setStatus($remaining > 0 ? BiInvoice::STATUS_PAID_PARTIAL : BiInvoice::STATUS_PAID);
        ;

        $em->persist($history);
        $em->flush();

        return $apiResponse->apiJsonResponse($obj, BiInvoice::INVOICE_READ);
    }

    /**
     * @Route("/download/{id}", name="download", options={"expose"=true}, methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Return message successful",
     * )
     * @OA\Response(
     *     response=403,
     *     description="Forbidden for not good role or user",
     * )
     *
     * @OA\Tag(name="Bill")
     *
     * @param $id
     * @param ApiResponse $apiResponse
     * @return JsonResponse
     * @throws MpdfException
     */
    public function download($id, ApiResponse $apiResponse): JsonResponse
    {
        $em = $this->doctrine->getManager();

        $obj = $em->getRepository(BiInvoice::class)->find($id);
        $this->billService->getInvoice($this->getUser(), [$obj]);

        return $apiResponse->apiJsonResponseSuccessful("ok");
    }
}
