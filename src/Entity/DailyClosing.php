<?php

namespace App\Entity;

use App\Repository\DailyClosingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DailyClosingRepository::class)]
class DailyClosing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $closingDate;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $morningOpening;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $morningClosing;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $eveningOpening;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $eveningClosing;

    #[ORM\Column(type: 'text', nullable: true)]
    private $pattern;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClosingDate(): ?\DateTimeInterface
    {
        return $this->closingDate;
    }

    public function setClosingDate(\DateTimeInterface $closingDate): self
    {
        $this->closingDate = $closingDate;

        return $this;
    }

    public function getMorningOpening(): ?string
    {
        return $this->morningOpening;
    }

    public function setMorningOpening(?string $morningOpening): self
    {
        $this->morningOpening = $morningOpening;

        return $this;
    }

    public function getMorningClosing(): ?string
    {
        return $this->morningClosing;
    }

    public function setMorningClosing(?string $morningClosing): self
    {
        $this->morningClosing = $morningClosing;

        return $this;
    }

    public function getEveningOpening(): ?string
    {
        return $this->eveningOpening;
    }

    public function setEveningOpening(?string $eveningOpening): self
    {
        $this->eveningOpening = $eveningOpening;

        return $this;
    }

    public function getEveningClosing(): ?string
    {
        return $this->eveningClosing;
    }

    public function setEveningClosing(?string $eveningClosing): self
    {
        $this->eveningClosing = $eveningClosing;

        return $this;
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    public function setPattern(?string $pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }
}
