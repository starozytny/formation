<?php

namespace App\Service\Data\Bill;

use App\Entity\Bill\BiItem;
use App\Entity\Bill\BiSociety;
use App\Service\Data\DataConstructor;

class DataItem extends DataConstructor
{
    public function setData(BiItem $obj, $data, BiSociety $society): BiItem
    {
        return ($obj)
            ->setSociety($society)

            ->setReference($this->sanitizeData->trimData($data->reference))
            ->setNumero($this->sanitizeData->trimData($data->numero))

            ->setName($this->sanitizeData->trimData($data->name))
            ->setContent($this->sanitizeData->trimData($data->content))

            ->setUnity($this->sanitizeData->trimData($data->unity, "pièce"))
            ->setPrice($this->sanitizeData->setToFloat($data->price))
            ->setRateTva($this->sanitizeData->setToFloat($data->rateTva, 0))
            ->setCodeTva($this->sanitizeData->setToInteger($data->codeTva, 0))
        ;
    }
}
