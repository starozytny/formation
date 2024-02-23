<?php

namespace App\Entity\Formation;

use App\Repository\Formation\FoTaxRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FoTaxRepository::class)]
class FoTax
{
    const LIST = ['tax_list'];
    const FORM = ['tax_form'];
    const SELECT = ['tax_select'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tax_list', 'tax_form'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['tax_list', 'tax_form'])]
    private ?int $code = null;

    #[ORM\Column]
    #[Groups(['tax_list', 'tax_form', 'tax_select'])]
    private ?float $taux = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['tax_list', 'tax_form'])]
    private ?string $numeroCompta = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): static
    {
        $this->taux = $taux;

        return $this;
    }

    public function getNumeroCompta(): ?string
    {
        return $this->numeroCompta;
    }

    public function setNumeroCompta(?string $numeroCompta): static
    {
        $this->numeroCompta = $numeroCompta;

        return $this;
    }
}
