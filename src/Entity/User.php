<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    const ROLE_USER = 'ROLE_USER';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password = '';

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\LearningModule", inversedBy="users")
     */
    private $badges;

    public function __construct()
    {
        $this->badges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getRoles()
    {
        return [self::ROLE_USER];
        // TODO: Implement getRoles() method.
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|LearningModule[]
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(LearningModule $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;
        }

        return $this;
    }

    public function removeBadge(LearningModule $badge): self
    {
        if ($this->badges->contains($badge)) {
            $this->badges->removeElement($badge);
        }

        return $this;
    }

}
