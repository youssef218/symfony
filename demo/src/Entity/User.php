<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
#[UniqueEntity('email')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\UserListener'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length( min : 2 , max : 50)]
    private ?string $fullName = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length( min : 2 , max : 50)]

    private ?string $pseudo = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Length( min : 2 , max : 180)]
    #[Assert\Email()]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private array $roles = [];
  
    private ?string $plainPassword = null;
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank()]

    private ?string $password = 'password';


    #[ORM\Column]
    #[Assert\NotNull()]

    private ?\DateTimeImmutable $creatAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Ingredient::class, orphanRemoval: true)]
    private Collection $ingredient;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
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
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    /**
     * Get the value of plainPassword
     */ 
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of plainPassword
     *
     * @return  self
     */ 
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
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

 

    public function getCreatAt(): ?\DateTimeImmutable
    {
        return $this->creatAt;
    }

    public function setCreatAt(\DateTimeImmutable $creatAt): self
    {
        $this->creatAt = $creatAt;

        return $this;
    }

      public function __construct()
    {
        $this->creatAt = new \DateTimeImmutable();
        $this->ingredient = new ArrayCollection();
    }

      /**
       * @return Collection<int, Ingredient>
       */
      public function getIngredient(): Collection
      {
          return $this->ingredient;
      }

      public function addIngredient(Ingredient $ingredient): self
      {
          if (!$this->ingredient->contains($ingredient)) {
              $this->ingredient->add($ingredient);
              $ingredient->setUser($this);
          }

          return $this;
      }

      public function removeIngredient(Ingredient $ingredient): self
      {
          if ($this->ingredient->removeElement($ingredient)) {
              // set the owning side to null (unless already changed)
              if ($ingredient->getUser() === $this) {
                  $ingredient->setUser(null);
              }
          }

          return $this;
      }

}
