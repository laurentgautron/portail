<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExperienceRepository::class)
 */
class Experience
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fonction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $beginAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $stopAt;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $contexte;

    /**
     * @ORM\Column(type="text")
     */
    private $realisation;

    /**
     * @ORM\Column(type="text")
     */
    private $techinque;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="exp")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

    public function getTechinque(): ?string
    {
        return $this->techinque;
    }

    public function setTechinque(string $techinque): self
    {
        $this->techinque = $techinque;

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
}
