<?php

namespace App\Entity;

use App\Repository\CompetencesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=CompetencesRepository::class)
 */
class Competences
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
    private $nomcompetence;

    /**
     * @ORM\OneToMany(targetEntity=UserCompetences::class, mappedBy="competences")
     */
    private $comp;

    public function __construct()
    {
        $this->comp = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomcompetence(): ?string
    {
        return $this->nomcompetence;
    }

    public function setNomcompetence(string $nomcompetence): self
    {
        $this->nomcompetence = $nomcompetence;

        return $this;
    }

    /**
     * @return Collection|UserCompetences[]
     */
    public function getComp(): Collection
    {
        return $this->comp;
    }

    public function addComp( UserCompetences $comp): self
    {
        if (!$this->comp->contains($comp)) {
            $this->comp[] = $comp;
            $comp->setCompetence($this);
        }

        return $this;
    }

    public function removeComp(UserCompetences $comp): self
    {
        if ($this->comp->removeElement($comp)) {
            // set the owning side to null (unless already changed)
            if ($comp->getCompetence() === $this) {
                $comp->setCompetence(null);
            }
        }

        return $this;
    }
}
