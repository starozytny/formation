<?php

namespace App\Entity\Formation;

use App\Repository\Formation\FoFormationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FoFormationRepository::class)]
class FoFormation
{
    const FOLDER_CONTENT = "fo_formations_content";

    const LIST = ['formation_list'];
    const FORM = ['formation_form'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['formation_list', 'formation_form'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['formation_list', 'formation_form'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['formation_list', 'formation_form'])]
    private ?bool $isOnline = false;

    #[ORM\Column]
    #[Groups(['formation_list', 'formation_form'])]
    private ?float $priceHt = null;

    #[ORM\Column]
    #[Groups(['formation_list', 'formation_form'])]
    private ?float $tva = null;

    #[ORM\Column]
    #[Groups(['formation_list', 'formation_form'])]
    private ?int $nbMin = null;

    #[ORM\Column]
    #[Groups(['formation_list', 'formation_form'])]
    private ?int $nbMax = null;

    #[ORM\Column]
    #[Groups(['formation_list', 'formation_form'])]
    private ?int $nbRemain = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['formation_list', 'formation_form'])]
    private ?\DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['formation_list', 'formation_form'])]
    private ?\DateTimeInterface $endAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['formation_form'])]
    private ?\DateTimeInterface $startTimeAm = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['formation_form'])]
    private ?\DateTimeInterface $endTimeAm = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['formation_form'])]
    private ?\DateTimeInterface $startTimePm = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['formation_form'])]
    private ?\DateTimeInterface $endTimePm = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['formation_form'])]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['formation_form'])]
    private ?string $address2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['formation_form'])]
    private ?string $complement = null;

    #[ORM\Column(length: 6, nullable: true)]
    #[Groups(['formation_form'])]
    private ?string $zipcode = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['formation_form'])]
    private ?string $city = null;

    #[ORM\Column]
    #[Groups(['formation_form'])]
    private ?int $type = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['formation_form'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['formation_form'])]
    private ?string $target = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['formation_form'])]
    private ?string $requis = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): static
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    public function getPriceHt(): ?float
    {
        return $this->priceHt;
    }

    public function setPriceHt(float $priceHt): static
    {
        $this->priceHt = $priceHt;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getNbMin(): ?int
    {
        return $this->nbMin;
    }

    public function setNbMin(int $nbMin): static
    {
        $this->nbMin = $nbMin;

        return $this;
    }

    public function getNbMax(): ?int
    {
        return $this->nbMax;
    }

    public function setNbMax(int $nbMax): static
    {
        $this->nbMax = $nbMax;

        return $this;
    }

    public function getNbRemain(): ?int
    {
        return $this->nbRemain;
    }

    public function setNbRemain(int $nbRemain): static
    {
        $this->nbRemain = $nbRemain;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getStartTimeAm(): ?\DateTimeInterface
    {
        return $this->startTimeAm;
    }

    public function setStartTimeAm(?\DateTimeInterface $startTimeAm): static
    {
        $this->startTimeAm = $startTimeAm;

        return $this;
    }

    public function getEndTimeAm(): ?\DateTimeInterface
    {
        return $this->endTimeAm;
    }

    public function setEndTimeAm(?\DateTimeInterface $endTimeAm): static
    {
        $this->endTimeAm = $endTimeAm;

        return $this;
    }

    public function getStartTimePm(): ?\DateTimeInterface
    {
        return $this->startTimePm;
    }

    public function setStartTimePm(?\DateTimeInterface $startTimePm): static
    {
        $this->startTimePm = $startTimePm;

        return $this;
    }

    public function getEndTimePm(): ?\DateTimeInterface
    {
        return $this->endTimePm;
    }

    public function setEndTimePm(?\DateTimeInterface $endTimePm): static
    {
        $this->endTimePm = $endTimePm;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): static
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): static
    {
        $this->complement = $complement;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function getRequis(): ?string
    {
        return $this->requis;
    }

    public function setRequis(?string $requis): static
    {
        $this->requis = $requis;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[Groups(['formation_list'])]
    public function getTypeString(): string
    {
        $values = ["présentiel", "en ligne"];
        return $values[$this->type];
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
