<?php

namespace App\Entity;

use App\Entity\Formation\FoRegistration;
use App\Entity\Formation\FoWorker;
use App\Entity\Paiement\PaBank;
use App\Entity\Paiement\PaOrder;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 */
class User extends DataEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    const FOLDER_AVATARS = "avatars";

    const ADMIN_READ = ['admin:read'];
    const USER_READ = ['user:read'];
    const VISITOR_READ = ['visitor:read'];

    const CODE_ROLE_USER = 0;
    const CODE_ROLE_DEVELOPER = 1;
    const CODE_ROLE_ADMIN = 2;
    const CODE_ROLE_MANAGER = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin:read", "count-users:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="alnum")
     * @Groups({"admin:read", "user:read", "count-users:read", "count-users:read"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Groups({"admin:read", "user:read", "count-users:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"admin:read"})
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read", "user:read", "count-users:read"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read", "user:read", "count-users:read"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $forgetCode;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $forgetAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read"})
     */
    private $token;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"admin:write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"admin:read", "user:read"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="user")
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity=FoWorker::class, mappedBy="user")
     */
    private $foWorkers;

    /**
     * @ORM\OneToMany(targetEntity=FoRegistration::class, mappedBy="user")
     */
    private $foRegistrations;

    /**
     * @ORM\OneToMany(targetEntity=PaBank::class, mappedBy="user")
     */
    private $paBanks;

    /**
     * @ORM\OneToMany(targetEntity=PaOrder::class, mappedBy="user")
     */
    private $paOrders;

    /**
     * @ORM\ManyToOne(targetEntity=Society::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"admin:read", "count-users:read"})
     */
    private $society;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->createdAt = $this->initNewDate();
        $this->token = $this->initToken();
        $this->notifications = new ArrayCollection();
        $this->foWorkers = new ArrayCollection();
        $this->foRegistrations = new ArrayCollection();
        $this->paBanks = new ArrayCollection();
        $this->paOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get label of the high role
     *
     * @return string
     * @Groups({"admin:read", "count-users:read"})
     */
    public function getHighRole(): string
    {
        $rolesSortedByImportance = ['ROLE_DEVELOPER', 'ROLE_ADMIN', 'ROLE_MANAGER', 'ROLE_USER', ];
        $rolesLabel = ['D??veloppeur', 'Administrateur', 'Manager', 'Utilisateur'];
        $i = 0;
        foreach ($rolesSortedByImportance as $role)
        {
            if (in_array($role, $this->roles))
            {
                return $rolesLabel[$i];
            }
            $i++;
        }

        return "Utilisateur";
    }

    /**
     * Get code of the high role
     *
     * @return int
     * @Groups({"admin:read", "count-users:read"})
     */
    public function getHighRoleCode(): int
    {
        switch($this->getHighRole()){
            case 'Manager':
                return self::CODE_ROLE_MANAGER;
            case 'D??veloppeur':
                return self::CODE_ROLE_DEVELOPER;
            case 'Administrateur':
                return self::CODE_ROLE_ADMIN;
            default:
                return self::CODE_ROLE_USER;
        }
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * return ll -> 5 janv. 2017
     * return LL -> 5 janvier 2017
     *
     * @return string|null
     * @Groups({"admin:read"})
     */
    public function getCreatedAtString(): ?string
    {
        return $this->getFullDateString($this->createdAt);
    }

    /**
     * How long ago a user was added.
     *
     * @return string
     * @Groups({"admin:read"})
     */
    public function getCreatedAtAgo(): string
    {
        return $this->getHowLongAgo($this->getCreatedAt(), 2);
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * How long ago a user was logged for the last time.
     *
     * @Groups({"admin:read"})
     */
    public function getLastLoginAgo(): ?string
    {
        return $this->getHowLongAgo($this->getLastLogin());
    }

    public function getForgetCode(): ?string
    {
        return $this->forgetCode;
    }

    public function setForgetCode(?string $forgetCode): self
    {
        $this->forgetCode = $forgetCode;

        return $this;
    }

    public function getForgetAt(): ?\DateTimeInterface
    {
        return $this->forgetAt;
    }

    public function setForgetAt(?\DateTimeInterface $forgetAt): self
    {
        $this->forgetAt = $forgetAt;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getHiddenEmail(): string
    {
        $email = $this->getEmail();
        $at = strpos($email, "@");
        $domain = substr($email, $at, strlen($email));
        $firstLetter = substr($email, 0, 1);
        $etoiles = "";
        for($i=1 ; $i < $at ; $i++){
            $etoiles .= "*";
        }
        return $firstLetter . $etoiles . $domain;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @return string
     * @Groups({"admin:read"})
     */
    public function getFullname(): string
    {
        return $this->getFullNameString($this->lastname, $this->firstname);
    }

    /**
     * @return string
     * @Groups({"admin:read", "count-users:read"})
     */
    public function getAvatarFile(): string
    {
        return $this->getFileOrDefault($this->avatar, self::FOLDER_AVATARS, "https://robohash.org/" . $this->username);
    }

    /**
     * @return Collection|FoWorker[]
     */
    public function getFoWorkers(): Collection
    {
        return $this->foWorkers;
    }

    public function addFoWorker(FoWorker $foWorker): self
    {
        if (!$this->foWorkers->contains($foWorker)) {
            $this->foWorkers[] = $foWorker;
            $foWorker->setUser($this);
        }

        return $this;
    }

    public function removeFoWorker(FoWorker $foWorker): self
    {
        if ($this->foWorkers->removeElement($foWorker)) {
            // set the owning side to null (unless already changed)
            if ($foWorker->getUser() === $this) {
                $foWorker->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FoRegistration[]
     */
    public function getFoRegistrations(): Collection
    {
        return $this->foRegistrations;
    }

    public function addFoRegistration(FoRegistration $foRegistration): self
    {
        if (!$this->foRegistrations->contains($foRegistration)) {
            $this->foRegistrations[] = $foRegistration;
            $foRegistration->setUser($this);
        }

        return $this;
    }

    public function removeFoRegistration(FoRegistration $foRegistration): self
    {
        if ($this->foRegistrations->removeElement($foRegistration)) {
            // set the owning side to null (unless already changed)
            if ($foRegistration->getUser() === $this) {
                $foRegistration->setUser(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|PaBank[]
     */
    public function getPaBanks(): Collection
    {
        return $this->paBanks;
    }

    public function addPaBank(PaBank $paBank): self
    {
        if (!$this->paBanks->contains($paBank)) {
            $this->paBanks[] = $paBank;
            $paBank->setUser($this);
        }

        return $this;
    }

    public function removePaBank(PaBank $paBank): self
    {
        if ($this->paBanks->removeElement($paBank)) {
            // set the owning side to null (unless already changed)
            if ($paBank->getUser() === $this) {
                $paBank->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PaOrder[]
     */
    public function getPaOrders(): Collection
    {
        return $this->paOrders;
    }

    public function addPaOrder(PaOrder $paOrder): self
    {
        if (!$this->paOrders->contains($paOrder)) {
            $this->paOrders[] = $paOrder;
            $paOrder->setUser($this);
        }

        return $this;
    }

    public function removePaOrder(PaOrder $paOrder): self
    {
        if ($this->paOrders->removeElement($paOrder)) {
            // set the owning side to null (unless already changed)
            if ($paOrder->getUser() === $this) {
                $paOrder->setUser(null);
            }
        }

        return $this;
    }

    public function getSociety(): ?Society
    {
        return $this->society;
    }

    public function setSociety(?Society $society): self
    {
        $this->society = $society;

        return $this;
    }
}
