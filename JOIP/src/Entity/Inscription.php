<?php

namespace App\Entity;
use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'inscriptions')]
    #[Assert\NotNull]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'inscriptions')]
    #[Assert\NotNull]
    private ?Apprenant $appenant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getAppenant(): ?Apprenant
    {
        return $this->appenant;
    }

    public function setAppenant(?Apprenant $appenant): self
    {
        $this->appenant = $appenant;

        return $this;
    }
}


