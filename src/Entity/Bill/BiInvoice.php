<?php

namespace App\Entity\Bill;

use App\Entity\DataEntity;
use App\Repository\Bill\BiInvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=BiInvoiceRepository::class)
 */
class BiInvoice extends DataEntity
{
    const PREFIX = "FA";

    const INVOICE_READ = ['invoice:read'];
    const CONTRACT_READ = ['invoice-contract:read'];

    const THEME_1 = 1;

    const STATUS_DRAFT = 0;
    const STATUS_TO_PAY = 1;
    const STATUS_PAID = 2;
    const STATUS_PAID_PARTIAL = 3;

    const DUE_TYPE_MANUAL = 0;
    const DUE_TYPE_ACQUITTED = 1;
    const DUE_TYPE_8 = 2;
    const DUE_TYPE_14 = 3;
    const DUE_TYPE_30 = 4;

    const PAY_TYPE_VIREMENT = 0;
    const PAY_TYPE_CHEQUE = 1;
    const PAY_TYPE_ESPECES = 2;
    const PAY_TYPE_CARTE = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"invoice:read", "invoice-contract:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoice:read"})
     */
    private $numero = "Z-Brouillon";

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoice:read"})
     */
    private $uid;

    /**
     * @ORM\Column(type="date")
     * @Groups({"invoice:read"})
     */
    private $dateAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"invoice:read"})
     */
    private $dueAt;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"invoice:read", "invoice-contract:read"})
     */
    private $dueType = self::DUE_TYPE_8;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"invoice:read", "invoice-contract:read"})
     */
    private $status = self::STATUS_DRAFT;

    /**
     * @ORM\Column(type="float")
     * @Groups({"invoice:read"})
     */
    private $totalHt;

    /**
     * @ORM\Column(type="float")
     * @Groups({"invoice:read"})
     */
    private $totalRemise;

    /**
     * @ORM\Column(type="float")
     * @Groups({"invoice:read"})
     */
    private $totalTva;

    /**
     * @ORM\Column(type="float")
     * @Groups({"invoice:read", "invoice-contract:read"})
     */
    private $totalTtc;

    /**
     * @ORM\Column(type="float")
     * @Groups({"invoice:read"})
     */
    private $toPay = 0;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoice:read"})
     */
    private $fromName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoice:read"})
     */
    private $fromAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $fromAddress2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $fromComplement;

    /**
     * @ORM\Column(type="string", length=40)
     * @Groups({"invoice:read"})
     */
    private $fromZipcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoice:read"})
     */
    private $fromCity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $fromCountry;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $fromPhone1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $fromEmail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fromSiren;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fromTva;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoice:read"})
     */
    private $toName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoice:read"})
     */
    private $toAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $toAddress2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $toComplement;

    /**
     * @ORM\Column(type="string", length=40)
     * @Groups({"invoice:read"})
     */
    private $toZipcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoice:read"})
     */
    private $toCity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $toCountry;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $toEmail;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $toPhone1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $siEmail;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $siPhone1;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"invoice:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=BiSociety::class, fetch="EAGER", inversedBy="biInvoices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $society;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"invoice:read"})
     */
    private $footer;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"invoice:read"})
     */
    private $note;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"invoice:read"})
     */
    private $noteProduct;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="integer")
     */
    private $theme = self::THEME_1;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"invoice:read"})
     */
    private $isSent = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"invoice:read"})
     */
    private $isSeen = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"invoice:read"})
     */
    private $isArchived = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"invoice:read"})
     */
    private $isHidden = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"invoice:read"})
     */
    private $isExported = false;

    /**
     * @ORM\OneToMany(targetEntity=BiHistory::class, mappedBy="invoice")
     */
    private $histories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fromBankName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fromBankNumero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fromBankTitulaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fromBankBic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fromBankCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fromBankIban;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"invoice:read"})
     */
    private $displayBank = true;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"invoice:read", "invoice-contract:read"})
     */
    private $payType = self::PAY_TYPE_VIREMENT;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"invoice:read"})
     */
    private $customerId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"invoice:read"})
     */
    private $siteId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $refCustomer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $refSite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $siName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $siAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $siAddress2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $siComplement;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $siZipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $siCity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $siCountry;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"invoice:read"})
     */
    private $quotationId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $refQuotation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"invoice:read"})
     */
    private $avoirId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $refAvoir;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"invoice:read", "invoice-contract:read"})
     */
    private $contractId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read"})
     */
    private $refContract;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"invoice:read", "invoice-contract:read"})
     */
    private $relationId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read", "invoice-contract:read"})
     */
    private $refRelation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"invoice:read", "invoice-contract:read"})
     */
    private $numRelation;

    public function __construct()
    {
        $this->createdAt = $this->initNewDate();
        $this->histories = new ArrayCollection();
        $this->uid = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDateAt(): ?\DateTimeInterface
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeInterface $dateAt): self
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    public function getDueAt(): ?\DateTimeInterface
    {
        return $this->dueAt;
    }

    public function setDueAt(?\DateTimeInterface $dueAt): self
    {
        $this->dueAt = $dueAt;

        return $this;
    }

    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    public function setFromName(string $fromName): self
    {
        $this->fromName = $fromName;

        return $this;
    }

    public function getFromAddress(): ?string
    {
        return $this->fromAddress;
    }

    public function setFromAddress(string $fromAddress): self
    {
        $this->fromAddress = $fromAddress;

        return $this;
    }

    public function getFromComplement(): ?string
    {
        return $this->fromComplement;
    }

    public function setFromComplement(?string $fromComplement): self
    {
        $this->fromComplement = $fromComplement;

        return $this;
    }

    public function getFromZipcode(): ?string
    {
        return $this->fromZipcode;
    }

    public function setFromZipcode(string $fromZipcode): self
    {
        $this->fromZipcode = $fromZipcode;

        return $this;
    }

    public function getFromCity(): ?string
    {
        return $this->fromCity;
    }

    public function setFromCity(string $fromCity): self
    {
        $this->fromCity = $fromCity;

        return $this;
    }

    public function getFromPhone1(): ?string
    {
        return $this->fromPhone1;
    }

    public function setFromPhone1(?string $fromPhone1): self
    {
        $this->fromPhone1 = $fromPhone1;

        return $this;
    }

    public function getFromEmail(): ?string
    {
        return $this->fromEmail;
    }

    public function setFromEmail(?string $fromEmail): self
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    public function getToName(): ?string
    {
        return $this->toName;
    }

    public function setToName(string $toName): self
    {
        $this->toName = $toName;

        return $this;
    }

    public function getToAddress(): ?string
    {
        return $this->toAddress;
    }

    public function setToAddress(string $toAddress): self
    {
        $this->toAddress = $toAddress;

        return $this;
    }

    public function getToComplement(): ?string
    {
        return $this->toComplement;
    }

    public function setToComplement(?string $toComplement): self
    {
        $this->toComplement = $toComplement;

        return $this;
    }

    public function getToZipcode(): ?string
    {
        return $this->toZipcode;
    }

    public function setToZipcode(string $toZipcode): self
    {
        $this->toZipcode = $toZipcode;

        return $this;
    }

    public function getToCity(): ?string
    {
        return $this->toCity;
    }

    public function setToCity(string $toCity): self
    {
        $this->toCity = $toCity;

        return $this;
    }

    public function getToEmail(): ?string
    {
        return $this->toEmail;
    }

    public function setToEmail(?string $toEmail): self
    {
        $this->toEmail = $toEmail;

        return $this;
    }

    public function getToPhone1(): ?string
    {
        return $this->toPhone1;
    }

    public function setToPhone1(?string $toPhone1): self
    {
        $this->toPhone1 = $toPhone1;

        return $this;
    }

    public function getSiEmail(): ?string
    {
        return $this->siEmail;
    }

    public function setSiEmail(?string $siEmail): self
    {
        $this->siEmail = $siEmail;

        return $this;
    }

    public function getSiPhone1(): ?string
    {
        return $this->siPhone1;
    }

    public function setSiPhone1(?string $siPhone1): self
    {
        $this->siPhone1 = $siPhone1;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        if($updatedAt){
            $updatedAt->setTimezone(new \DateTimeZone("Europe/Paris"));
        }
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string|null
     * @Groups({"invoice:read"})
     */
    public function getDateAtString(): ?string
    {
        return $this->getFullDateString($this->dateAt, "ll", false);
    }

    /**
     * @return string|null
     * @Groups({"invoice:read"})
     */
    public function getDueAtString(): ?string
    {
        return $this->getFullDateString($this->dueAt, "ll", false);
    }

    /**
     * @return string|null
     * @Groups({"invoice:read", "invoice-contract:read"})
     */
    public function getDateAtJavascript(): ?string
    {
        return $this->setDateJavascript($this->dateAt);
    }

    /**
     * @return string|null
     * @Groups({"invoice:read", "invoice-contract:read"})
     */
    public function getDueAtJavascript(): ?string
    {
        return $this->setDateJavascript($this->dueAt);
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalHt(): ?float
    {
        return $this->totalHt;
    }

    public function setTotalHt(float $totalHt): self
    {
        $this->totalHt = $totalHt;

        return $this;
    }

    public function getTotalTva(): ?float
    {
        return $this->totalTva;
    }

    public function setTotalTva(float $totalTva): self
    {
        $this->totalTva = $totalTva;

        return $this;
    }

    public function getTotalTtc(): ?float
    {
        return $this->totalTtc;
    }

    public function setTotalTtc(float $totalTtc): self
    {
        $this->totalTtc = $totalTtc;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getNoteProduct(): ?string
    {
        return $this->noteProduct;
    }

    public function setNoteProduct(?string $noteProduct): self
    {
        $this->noteProduct = $noteProduct;

        return $this;
    }

    /**
     * @return string
     * @Groups({"invoice:read"})
     */
    public function getStatusString(): string
    {
        $values = ["Brouillon", "A régler", "Payée", "Partiel"];

        return $values[$this->status];
    }

    public function getSociety(): ?BiSociety
    {
        return $this->society;
    }

    public function setSociety(?BiSociety $society): self
    {
        $this->society = $society;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getFromSiren(): ?string
    {
        return $this->fromSiren;
    }

    public function setFromSiren(?string $fromSiren): self
    {
        $this->fromSiren = $fromSiren;

        return $this;
    }

    public function getFromTva(): ?string
    {
        return $this->fromTva;
    }

    public function setFromTva(?string $fromTva): self
    {
        $this->fromTva = $fromTva;

        return $this;
    }

    public function getFooter(): ?string
    {
        return $this->footer;
    }

    public function setFooter(?string $footer): self
    {
        $this->footer = $footer;

        return $this;
    }

    public function getTheme(): ?int
    {
        return $this->theme;
    }

    public function setTheme(int $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getTotalRemise(): ?float
    {
        return $this->totalRemise;
    }

    public function setTotalRemise(float $totalRemise): self
    {
        $this->totalRemise = $totalRemise;

        return $this;
    }

    /**
     * @return string
     * @Groups({"invoice:read"})
     */
    public function getDueTypeString(): string
    {
        $values = ["Manuel", "Acquittée", "8 jours", "14 jours", "30 jours"];

        return $values[$this->dueType];
    }

    public function getDueType(): ?int
    {
        return $this->dueType;
    }

    public function setDueType(int $dueType): self
    {
        $this->dueType = $dueType;

        return $this;
    }

    /**
     * @return string
     * @Groups({"invoice:read"})
     */
    public function getIdentifiant(): string
    {
        return self::PREFIX . "-" . $this->uid;
    }

    public function getIsSent(): ?bool
    {
        return $this->isSent;
    }

    public function setIsSent(bool $isSent): self
    {
        $this->isSent = $isSent;

        return $this;
    }

    public function getIsSeen(): ?bool
    {
        return $this->isSeen;
    }

    public function setIsSeen(bool $isSeen): self
    {
        $this->isSeen = $isSeen;

        return $this;
    }

    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    public function getIsHidden(): ?bool
    {
        return $this->isHidden;
    }

    public function setIsHidden(bool $isHidden): self
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    public function getIsExported(): ?bool
    {
        return $this->isExported;
    }

    public function setIsExported(bool $isExported): self
    {
        $this->isExported = $isExported;

        return $this;
    }

    public function getToPay(): ?float
    {
        return $this->toPay;
    }

    public function setToPay(float $toPay): self
    {
        $this->toPay = $toPay;

        return $this;
    }

    /**
     * @return Collection<int, BiHistory>
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(BiHistory $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setInvoice($this);
        }

        return $this;
    }

    public function removeHistory(BiHistory $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getInvoice() === $this) {
                $history->setInvoice(null);
            }
        }

        return $this;
    }

    public function getFromCountry(): ?string
    {
        return $this->fromCountry;
    }

    public function setFromCountry(?string $fromCountry): self
    {
        $this->fromCountry = $fromCountry;

        return $this;
    }

    public function getToCountry(): ?string
    {
        return $this->toCountry;
    }

    public function setToCountry(?string $toCountry): self
    {
        $this->toCountry = $toCountry;

        return $this;
    }

    public function getFromBankName(): ?string
    {
        return $this->fromBankName;
    }

    public function setFromBankName(?string $fromBankName): self
    {
        $this->fromBankName = $fromBankName;

        return $this;
    }

    public function getFromBankNumero(): ?string
    {
        return $this->fromBankNumero;
    }

    public function setFromBankNumero(?string $fromBankNumero): self
    {
        $this->fromBankNumero = $fromBankNumero;

        return $this;
    }

    public function getFromBankTitulaire(): ?string
    {
        return $this->fromBankTitulaire;
    }

    public function setFromBankTitulaire(?string $fromBankTitulaire): self
    {
        $this->fromBankTitulaire = $fromBankTitulaire;

        return $this;
    }

    public function getFromBankBic(): ?string
    {
        return $this->fromBankBic;
    }

    public function setFromBankBic(?string $fromBankBic): self
    {
        $this->fromBankBic = $fromBankBic;

        return $this;
    }

    public function getFromBankCode(): ?string
    {
        return $this->fromBankCode;
    }

    public function setFromBankCode(?string $fromBankCode): self
    {
        $this->fromBankCode = $fromBankCode;

        return $this;
    }

    public function getFromBankIban(): ?string
    {
        return $this->cryptBank('decrypt', $this->fromBankIban);
    }

    public function setFromBankIban(?string $fromBankIban): self
    {
        $this->fromBankIban = $this->cryptBank('encrypt', $fromBankIban);

        return $this;
    }

    public function getDisplayBank(): ?bool
    {
        return $this->displayBank;
    }

    public function setDisplayBank(bool $displayBank): self
    {
        $this->displayBank = $displayBank;

        return $this;
    }

    public function getToAddress2(): ?string
    {
        return $this->toAddress2;
    }

    public function setToAddress2(?string $toAddress2): self
    {
        $this->toAddress2 = $toAddress2;

        return $this;
    }

    public function getFromAddress2(): ?string
    {
        return $this->fromAddress2;
    }

    public function setFromAddress2(?string $fromAddress2): self
    {
        $this->fromAddress2 = $fromAddress2;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(?int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getSiteId(): ?int
    {
        return $this->siteId;
    }

    public function setSiteId(?int $siteId): self
    {
        $this->siteId = $siteId;

        return $this;
    }

    public function getSiName(): ?string
    {
        return $this->siName;
    }

    public function setSiName(?string $siName): self
    {
        $this->siName = $siName;

        return $this;
    }

    public function getSiAddress(): ?string
    {
        return $this->siAddress;
    }

    public function setSiAddress(?string $siAddress): self
    {
        $this->siAddress = $siAddress;

        return $this;
    }

    public function getSiAddress2(): ?string
    {
        return $this->siAddress2;
    }

    public function setSiAddress2(?string $siAddress2): self
    {
        $this->siAddress2 = $siAddress2;

        return $this;
    }

    public function getSiComplement(): ?string
    {
        return $this->siComplement;
    }

    public function setSiComplement(?string $siComplement): self
    {
        $this->siComplement = $siComplement;

        return $this;
    }

    public function getSiZipcode(): ?string
    {
        return $this->siZipcode;
    }

    public function setSiZipcode(?string $siZipcode): self
    {
        $this->siZipcode = $siZipcode;

        return $this;
    }

    public function getSiCity(): ?string
    {
        return $this->siCity;
    }

    public function setSiCity(?string $siCity): self
    {
        $this->siCity = $siCity;

        return $this;
    }

    public function getSiCountry(): ?string
    {
        return $this->siCountry;
    }

    public function setSiCountry(?string $siCountry): self
    {
        $this->siCountry = $siCountry;

        return $this;
    }

    public function getRefCustomer(): ?string
    {
        return $this->refCustomer;
    }

    public function setRefCustomer(?string $refCustomer): self
    {
        $this->refCustomer = $refCustomer;

        return $this;
    }

    public function getRefSite(): ?string
    {
        return $this->refSite;
    }

    public function setRefSite(?string $refSite): self
    {
        $this->refSite = $refSite;

        return $this;
    }

    public function getQuotationId(): ?int
    {
        return $this->quotationId;
    }

    public function setQuotationId(?int $quotationId): self
    {
        $this->quotationId = $quotationId;

        return $this;
    }

    public function getRefQuotation(): ?string
    {
        return $this->refQuotation;
    }

    public function setRefQuotation(?string $refQuotation): self
    {
        $this->refQuotation = $refQuotation;

        return $this;
    }

    public function getRelationId(): ?int
    {
        return $this->relationId;
    }

    public function setRelationId(?int $relationId): self
    {
        $this->relationId = $relationId;

        return $this;
    }

    public function getRefRelation(): ?string
    {
        return $this->refRelation;
    }

    public function setRefRelation(?string $refRelation): self
    {
        $this->refRelation = $refRelation;

        return $this;
    }

    public function getNumRelation(): ?string
    {
        return $this->numRelation;
    }

    public function setNumRelation(?string $numRelation): self
    {
        $this->numRelation = $numRelation;

        return $this;
    }

    public function getContractId(): ?int
    {
        return $this->contractId;
    }

    public function setContractId(?int $contractId): self
    {
        $this->contractId = $contractId;

        return $this;
    }

    public function getRefContract(): ?string
    {
        return $this->refContract;
    }

    public function setRefContract(?string $refContract): self
    {
        $this->refContract = $refContract;

        return $this;
    }

    public function getAvoirId(): ?int
    {
        return $this->avoirId;
    }

    public function setAvoirId(?int $avoirId): self
    {
        $this->avoirId = $avoirId;

        return $this;
    }

    public function getRefAvoir(): ?string
    {
        return $this->refAvoir;
    }

    public function setRefAvoir(?string $refAvoir): self
    {
        $this->refAvoir = $refAvoir;

        return $this;
    }

    /**
     * @return string
     * @Groups({"invoice:read"})
     */
    public function getPayTypeString(): string
    {
        return $this->getPayTypeFullString($this->payType);
    }

    public function getPayType(): ?int
    {
        return $this->payType;
    }

    public function setPayType(int $payType): self
    {
        $this->payType = $payType;

        return $this;
    }
}
