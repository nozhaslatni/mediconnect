<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use App\Entity\User;
use App\Entity\Docteur;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateHeure = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = 'EN_ATTENTE';

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;   // patient = User

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Docteur $doctor = null;

    #[ORM\OneToOne(mappedBy: 'appointment', cascade: ['persist', 'remove'])]
    private ?Prescription $prescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeure(): ?\DateTimeInterface
    {
        return $this->dateHeure;
    }

    public function setDateHeure(\DateTimeInterface $dateHeure): static
    {
        $this->dateHeure = $dateHeure;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDoctor(): ?Docteur
    {
        return $this->doctor;
    }

    public function setDoctor(?Docteur $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getPrescription(): ?Prescription
    {
        return $this->prescription;
    }

    public function setPrescription(?Prescription $prescription): static
    {
        if ($prescription && $prescription->getAppointment() !== $this) {
            $prescription->setAppointment($this);
        }

        $this->prescription = $prescription;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            'Rdv #%d - %s',
            $this->id,
            $this->dateHeure?->format('d/m/Y H:i') ?? ''
        );
    }
}
