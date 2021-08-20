<?php

namespace App\Entity;

class Search
{
    /**
    * @ORM\Column(type="string", length=255)
    * @Assert\NotBlank
    * message = " vous devez renseigner un nom d'utilisateur"
    */
    private $nom;

    public function getNom(): ?string
        {
            return $this->nom;
        }

        public function setNom(string $nom): self
        {
            $this->nom = $nom;

            return $this;
        }
}

