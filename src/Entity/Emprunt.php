<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(name: "date_emprunt", type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEmprunt = null;

    #[ORM\Column(name: "date_fin_prevue", type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFinPrevue = null;

    #[ORM\Column(name: "date_retour", type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRetour = null;

    #[ORM\ManyToOne(targetEntity: Adherent::class, inversedBy: 'emprunts')]
    #[ORM\JoinColumn(name: "adherent_id", referencedColumnName: "id", nullable: true)]
    private ?Adherent $adherent = null;

    #[ORM\ManyToOne(targetEntity: Livre::class, inversedBy: 'emprunts')]
    #[ORM\JoinColumn(name: "livre_id", referencedColumnName: "id", nullable: true)]
    private ?Livre $livre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->dateEmprunt;
    }

    public function setDateEmprunt(?\DateTimeInterface $dateEmprunt): self
    {
        $this->dateEmprunt = $dateEmprunt;

        return $this;
    }

    public function getDateFinPrevue(): ?\DateTimeInterface
    {
        return $this->dateFinPrevue;
    }

    public function setDateFinPrevue(?\DateTimeInterface $dateFinPrevue): self
    {
        $this->dateFinPrevue = $dateFinPrevue;

        return $this;
    }


    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->date_retour;
    }

    public function setDateRetour(\DateTimeInterface $date_retour): self
    {
        $this->date_retour = $date_retour;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }
}
