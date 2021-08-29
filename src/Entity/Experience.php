<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=App\Repository\ExperienceRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Experience
{
    use TimeStampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * message="veuillez indiquer la fonction que vous aviez"
     * 
     */
    private $fonction;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * message="veuillez indiquer lle lieu où vous avez travaillé"
     */
    private $lieu;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\NotBlank
     * message="veuillez indiquer la date de début"
     */
    private $beginAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * 
     */
    private $stopAt;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * message="veuillez indiquer une description de votre travail"
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * message="veuillez indiquer le contexte dans lequel vous avez effectué votre mission"
     */
    private $contexte;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * message="veuillez indiquer les projets réalisés"
     */
    private $realisation;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * message="veuillez indiquer les techniques utilisées"
     */
    private $technique;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="exp")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="experience")
     */
    private $entreprise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getBeginAt(): ?\DateTimeImmutable
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTimeImmutable $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getStopAt(): ?\DateTimeImmutable
    {
        return $this->stopAt;
    }

    public function setStopAt(?\DateTimeImmutable $stopAt): self
    {
        $this->stopAt = $stopAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    public function setContexte(string $contexte): self
    {
        $this->contexte = $contexte;

        return $this;
    }

    public function getRealisation(): ?string
    {
        return $this->realisation;
    }

    public function setRealisation(string $realisation): self
    {
        $this->realisation = $realisation;

        return $this;
    }

    public function getTechnique(): ?string
    {
        return $this->technique;
    }

    public function setTechnique(string $technique): self
    {
        $this->technique = $technique;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }
}
