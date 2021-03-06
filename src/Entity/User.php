<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampable;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=App\Repository\UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    use TimeStampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *  @Assert\NotBlank
     *  message = "Vous devez saisir une adresse mail !"
     * 
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * 
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *  @Assert\NotBlank
     * message= "vous decez choisir un mot de passe"
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * message = " vous devez renseigner un nom d'utilisateur"
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * message = "vous devez renseigner un prenom utilisateur"
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * message = "vous devez renseigner une adresse"
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * message = "entrez votre code postal"
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank
     * message="vous devez renseigner un num??ro de t??l??phone"
     */
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity=Experience::class, mappedBy="user")
     */
    private $exp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity=UserCompetences::class, mappedBy="user")
     */
    private $userComp;

    /**
     * @ORM\OneToOne(targetEntity=Lastconnexion::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $lastconnexion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $asLeft;

    /**
     * @ORM\OneToMany(targetEntity=Documents::class, mappedBy="user")
     */
    private $documents;

    public function __construct()
    {
        $this->exp = new ArrayCollection();
        $this->userComp = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(string $roles): self
    {
        $this->roles = [$roles];

        return $this;
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection|Experience[]
     */
    public function getExp(): Collection
    {
        return $this->exp;
    }

    public function addExp(Experience $exp): self
    {
        if (!$this->exp->contains($exp)) {
            $this->exp[] = $exp;
            $exp->setUser($this);
        }

        return $this;
    }

    public function removeExp(Experience $exp): self
    {
        if ($this->exp->removeElement($exp)) {
            // set the owning side to null (unless already changed)
            if ($exp->getUser() === $this) {
                $exp->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserCompetences[]
     */
    public function getUserComp(): Collection
    {
        return $this->userComp;
    }

    public function addUserComp(UserCompetences $userComp): self
    {
        if (!$this->userComp->contains($userComp)) {
            $this->userComp[] = $userComp;
            $userComp->setUser($this);
        }

        return $this;
    }

    public function removeUserComp(UserCompetences $userComp): self
    {
        if ($this->userComp->removeElement($userComp)) {
            // set the owning side to null (unless already changed)
            if ($userComp->getUser() === $this) {
                $userComp->setUser(null);
            }
        }

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getLastconnexion(): ?Lastconnexion
    {
        return $this->lastconnexion;
    }

    public function setLastconnexion(?Lastconnexion $lastconnexion): self
    {
        // unset the owning side of the relation if necessary
        if ($lastconnexion === null && $this->lastconnexion !== null) {
            $this->lastconnexion->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($lastconnexion !== null && $lastconnexion->getUser() !== $this) {
            $lastconnexion->setUser($this);
        }

        $this->lastconnexion = $lastconnexion;

        return $this;
    }

    public function getAsLeft(): ?bool
    {
        return $this->asLeft;
    }

    public function setAsLeft(?bool $asLeft): self
    {
        $this->asLeft = $asLeft;

        return $this;
    }

    /**
     * @return Collection|Documents[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Documents $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setUser($this);
        }

        return $this;
    }

    public function removeDocument(Documents $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getUser() === $this) {
                $document->setUser(null);
            }
        }

        return $this;
    }

}
