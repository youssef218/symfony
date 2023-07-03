<?php

namespace App\Entity;

use App\Repository\OrganisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: OrganisateurRepository::class)]
class Organisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $nomSociete = null;

    #[ORM\Column(length: 50)]
    private ?string $nfiscale = null;

    #[ORM\Column(length: 50)]
    private ?string $ville = null;

    #[ORM\Column(length: 20)]
    private ?string $tele = null;

    #[ORM\Column(length: 50)]
    private ?string $sujet = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $creatAt = null;

    #[ORM\OneToMany(mappedBy: 'organisateur', targetEntity: Evenenment::class)]
    private Collection $evenenments;

    public function __construct()
    {
        $this->creatAt = new \DateTimeImmutable();
        $this->roles = ['ROLE_ORGANISATEUR'];
        $this->evenenments = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomSociete(): ?string
    {
        return $this->nomSociete;
    }

    public function setNomSociete(string $nomSociete): self
    {
        $this->nomSociete = $nomSociete;

        return $this;
    }

    public function getNfiscale(): ?string
    {
        return $this->nfiscale;
    }

    public function setNfiscale(string $nfiscale): self
    {
        $this->nfiscale = $nfiscale;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTele(): ?string
    {
        return $this->tele;
    }

    public function setTele(string $tele): self
    {
        $this->tele = $tele;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getCreatAt(): ?\DateTimeImmutable
    {
        return $this->creatAt;
    }

    public function setCreatAt(\DateTimeImmutable $creatAt): self
    {
        $this->creatAt = $creatAt;

        return $this;
    }

    /**
     * @return Collection<int, Evenenment>
     */
    public function getEvenenments(): Collection
    {
        return $this->evenenments;
    }

    public function addEvenenment(Evenenment $evenenment): self
    {
        if (!$this->evenenments->contains($evenenment)) {
            $this->evenenments->add($evenenment);
            $evenenment->setOrganisateur($this);
        }

        return $this;
    }

    public function removeEvenenment(Evenenment $evenenment): self
    {
        if ($this->evenenments->removeElement($evenenment)) {
            // set the owning side to null (unless already changed)
            if ($evenenment->getOrganisateur() === $this) {
                $evenenment->setOrganisateur(null);
            }
        }

        return $this;
    }
}
