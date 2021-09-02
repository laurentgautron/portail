<?php

namespace App\Entity;

use App\Repository\LastconnexionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LastconnexionRepository::class)
 */
class Lastconnexion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $lasLogoutAt;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="lastconnexion", cascade={"persist", "remove"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLasLogoutAt(): ?\DateTimeImmutable
    {
        return $this->lasLogoutAt;
    }

    public function setLasLogoutAt(?\DateTimeImmutable $lasLogoutAt): self
    {
        $this->lasLogoutAt = $lasLogoutAt;

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
