<?php

namespace App\Service\Data;

use App\Entity\Formation\FoFormation;
use App\Entity\Formation\FoNews;
use App\Entity\Formation\FoTax;
use App\Entity\Formation\FoWorker;
use App\Repository\Formation\FoFormationRepository;
use App\Repository\Formation\FoNewsRepository;
use App\Service\SanitizeData;

class DataFormation
{
    public function __construct(
        private readonly SanitizeData $sanitizeData
    ) {}

    private function setSlug($repository, $obj, $slug, $text)
    {
        $existe = $repository->findOneBy(['slug' => $slug]);
        if($existe){
            $i = 1;
            while ($existe && $existe->getId() != $obj->getId()){
                $obj->setSlug($this->sanitizeData->fullSanitize($text . '-' . $i++));
                $existe = $repository->findOneBy(['slug' => $obj->getSlug()]);
            }
        }

        return $obj;
    }

    public function setDataNews(FoNews $obj, $data, FoNewsRepository $repository): FoNews
    {
        $obj = ($obj)
            ->setName($this->sanitizeData->trimData($data->name))
            ->setSlug($this->sanitizeData->fullSanitize($data->name))
        ;
        $obj = $this->setSlug($repository, $obj, $obj->getSlug(), $obj->getName());

        return ($obj)
            ->setVisibility((int) $data->visibility)
            ->setContent($this->sanitizeData->trimData($data->content->html))
        ;
    }

    public function setDataFormation(FoFormation $obj, $data, FoFormationRepository $repository): FoFormation
    {
        $startTimeAm = $data->startTimeAm ? str_replace('h', ':', $data->startTimeAm) : null;
        $endTimeAm = $data->endTimeAm ? str_replace('h', ':', $data->endTimeAm) : null;
        $startTimePm = $data->startTimePm ? str_replace('h', ':', $data->startTimePm) : null;
        $endTimePm = $data->endTimePm ? str_replace('h', ':', $data->endTimePm) : null;

        $obj = ($obj)
            ->setName($this->sanitizeData->trimData($data->name))
            ->setSlug($this->sanitizeData->fullSanitize($data->name))
        ;
        $obj = $this->setSlug($repository, $obj, $obj->getSlug(), $obj->getName());

        return ($obj)
            ->setPriceHt((float) $data->priceHt)
            ->setTva((float) $data->tva)
            ->setNbMin((int) $data->nbMin)
            ->setNbMax((int) $data->nbMax)
            ->setNbRemain((int) $data->nbRemain)
            ->setStartAt($this->sanitizeData->createDatePicker($data->startAt))
            ->setEndAt($this->sanitizeData->createDatePicker($data->endAt))
            ->setStartTimeAm($startTimeAm ? $this->sanitizeData->createDateTimePicker($data->startAt . " " . $startTimeAm) : null)
            ->setEndTimeAm($endTimeAm ? $this->sanitizeData->createDateTimePicker($data->startAt . " " . $endTimeAm) : null)
            ->setStartTimePm($startTimePm ? $this->sanitizeData->createDateTimePicker($data->startAt . " " . $startTimePm) : null)
            ->setEndTimePm($endTimePm ? $this->sanitizeData->createDateTimePicker($data->startAt . " " . $endTimePm) : null)
            ->setAddress($this->sanitizeData->trimData($data->address))
            ->setAddress2($this->sanitizeData->trimData($data->address2))
            ->setComplement($this->sanitizeData->trimData($data->complement))
            ->setZipcode($this->sanitizeData->trimData($data->zipcode))
            ->setCity($this->sanitizeData->trimData($data->city))
            ->setType((int) $data->type)
            ->setContent($this->sanitizeData->trimData($data->content->html))
            ->setTarget($this->sanitizeData->trimData($data->target->html))
            ->setRequis($this->sanitizeData->trimData($data->requis->html))
        ;
    }

    public function setDataTax(FoTax $obj, $data): FoTax
    {
        return ($obj)
            ->setCode((int) $data->code)
            ->setTaux((float) $data->taux)
            ->setNumeroCompta($this->sanitizeData->trimData($data->numeroCompta))
        ;
    }

    public function setDataWorker(FoWorker $obj, $data, $context): FoWorker
    {
        if($context == "create"){
            $obj = ($obj)
                ->setLastname($this->sanitizeData->trimData($data->lastname))
                ->setFirstname($this->sanitizeData->trimData($data->firstname))
                ->setType((int) $data->type)
            ;
        }

        return ($obj)
            ->setEmail($this->sanitizeData->trimData($data->email))
        ;
    }
}
