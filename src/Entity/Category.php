<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Competences::class, mappedBy="category")
     */
    private $competence;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bydefault;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Competences[]
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competences $competence): self
    {
        if (!$this->competence->contains($competence)) {
            $this->competence[] = $competence;
            $competence->setCategory($this);
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): self
    {
        if ($this->competence->removeElement($competence)) {
            // set the owning side to null (unless already changed)
            if ($competence->getCategory() === $this) {
                $competence->setCategory(null);
            }
        }

        return $this;
    }

    public function getBydefault(): ?bool
    {
        return $this->bydefault;
    }

    public function setBydefault(bool $bydefault): self
    {
        $this->bydefault = $bydefault;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
