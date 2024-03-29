<?php

namespace App\Entity\Formation;

use App\Repository\Formation\FoParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FoParticipantRepository::class)]
class FoParticipant
{
    const LIST = ['participant_list'];
    const REGISTERED = ['participant_registered'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['participant_list'])]
    private ?int $id = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['participant_list'])]
    private ?FoOrder $foOrder = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['participant_list', 'participant_registered'])]
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
