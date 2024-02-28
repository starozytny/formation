<?php

namespace App\Entity\Formation;

use App\Entity\DataEntity;
use App\Repository\Formation\FoNewsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FoNewsRepository::class)]
class FoNews extends DataEntity
{
    const FOLDER = "fo_news";
    const FOLDER_CONTENT = "fo_news_content";

    const LIST = ['news_list'];
    const FORM = ['news_form'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['news_list', 'news_form'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['news_list', 'news_form'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['news_list', 'news_form'])]
    private ?int $visibility = null;

    #[ORM\Column(length: 255)]
    #[Groups(['news_list', 'news_form'])]
    private ?string $file = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['news_form'])]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    #[Groups(['news_list'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['news_list'])]
    private ?\DateTimeInterface $updatedAt = null;

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

    public function getVisibility(): ?int
    {
        return $this->visibility;
    }

    public function setVisibility(int $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): static
    {
        $this->file = $file;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    #[Groups(['news_list'])]
    public function getVisibilityString(): ?string
    {
        $values = ["Tout le monde", "Utilisateurs"];
        return $values[$this->visibility];
    }

    #[Groups(['news_list', 'news_form'])]
    public function getFileFile(): ?string
    {
        return $this->getFileOrDefault($this->file, self::FOLDER, null);
    }
}
