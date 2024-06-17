<?php

namespace App\Entity;

use App\Repository\WeeklyScheduleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeeklyScheduleRepository::class)]
class WeeklySchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 10)]
    private $day;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $morningOpening;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $morningClosing;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $eveningOpening;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $eveningClosing;

    #[ORM\Column(type: 'string', length: 10)]
    private $dayEnglish;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

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

    public function getDayEnglish(): ?string
    {
        return $this->dayEnglish;
    }

    public function setDayEnglish(string $dayEnglish): self
    {
        $this->dayEnglish = $dayEnglish;

        return $this;
    }
}
