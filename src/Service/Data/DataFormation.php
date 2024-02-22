<?php

namespace App\Service\Data;

use App\Entity\Formation\FoNews;
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
}
