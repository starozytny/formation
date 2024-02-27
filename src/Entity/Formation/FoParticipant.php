<?php

namespace App\Entity\Formation;

use App\Repository\Formation\FoParticipantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FoParticipantRepository::class)]
class FoParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FoOrder $foOrder = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FoWorker $worker = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFoOrder(): ?FoOrder
    {
        return $this->foOrder;
    }

    public function setFoOrder(?FoOrder $foOrder): static
    {
        $this->foOrder = $foOrder;

        return $this;
    }

    public function getWorker(): ?FoWorker
    {
        return $this->worker;
    }

    public function setWorker(?FoWorker $worker): static
    {
        $this->worker = $worker;

        return $this;
    }
}
