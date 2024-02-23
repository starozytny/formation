<?php

namespace App\Service\Data;

use App\Entity\Formation\FoFormation;
use App\Entity\Formation\FoNews;
use App\Entity\Formation\FoTax;
use App\Service\SanitizeData;

class DataFormation
{
    public function __construct(
        private readonly SanitizeData $sanitizeData
    ) {}

    public function setDataNews(FoNews $obj, $data): FoNews
    {
        return ($obj)
            ->setName($this->sanitizeData->trimData($data->name))
            ->setVisibility((int) $data->visibility)
            ->setContent($this->sanitizeData->trimData($data->content->html))
        ;
    }

    public function setDataFormation(FoFormation $obj, $data): FoFormation
    {
        return ($obj)
            ->setName($this->sanitizeData->trimData($data->name))
            ->setContent($this->sanitizeData->trimData($data->content->html))
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
}
