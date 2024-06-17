<?php

namespace App\Entity;

use App\Repository\MultiDayClosingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MultiDayClosingRepository::class)]
class MultiDayClosing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $beginClosing;

    #[ORM\Column(type: 'date')]
    private $finisgClosing;

    #[ORM\Column(type: 'text', nullable: true)]
    private $pattern;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBeginClosing(): ?\DateTimeInterface
    {
        return $this->beginClosing;
    }

    public function setBeginClosing(\DateTimeInterface $beginClosing): self
    {
        $this->beginClosing = $beginClosing;

        return $this;
    }

    public function getFinisgClosing(): ?\DateTimeInterface
    {
        return $this->finisgClosing;
    }

    public function setFinisgClosing(\DateTimeInterface $finisgClosing): self
    {
        $this->finisgClosing = $finisgClosing;

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
