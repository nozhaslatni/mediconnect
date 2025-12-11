<?php

namespace App\Entity;

use App\Repository\SpecialtyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialtyRepository::class)]
class Specialty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Docteur>
     */
    #[ORM\OneToMany(mappedBy: 'specialty', targetEntity: Docteur::class)]
    private Collection $docteurs;

    public function __construct()
    {
        $this->docteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Docteur>
     */
    public function getDocteurs(): Collection
    {
        return $this->docteurs;
    }

    public function addDocteur(Docteur $docteur): static
    {
        if (!$this->docteurs->contains($docteur)) {
            $this->docteurs->add($docteur);
            $docteur->setSpecialty($this);
        }

        return $this;
    }

    public function removeDocteur(Docteur $docteur): static
    {
        if ($this->docteurs->removeElement($docteur)) {
            if ($docteur->getSpecialty() === $this) {
                $docteur->setSpecialty(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->nom;
    }
}
