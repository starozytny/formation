<?php

namespace App\Entity\Formation;

use App\Entity\Enum\Formation\WorkerType;
use App\Entity\Main\User;
use App\Repository\Formation\FoWorkerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FoWorkerRepository::class)]
class FoWorker
{
    const LIST = ['worker_list'];
    const FORM = ['worker_form'];
    const SELECT = ['worker_select'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['worker_list', 'worker_form', 'worker_select'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['worker_list', 'worker_form', 'worker_select'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['worker_list', 'worker_form', 'worker_select'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['worker_list', 'worker_form', 'worker_select'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['worker_list', 'worker_form', 'worker_select'])]
    private ?int $type = WorkerType::Employee;

    #[ORM\ManyToOne(inversedBy: 'foWorkers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
