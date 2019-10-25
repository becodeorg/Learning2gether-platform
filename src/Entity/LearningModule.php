<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LearningModuleRepository")
 */
class LearningModule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $badge;

    /**
     * @ORM\OneToMany(targetEntity="LearningModuleTranslation", mappedBy="learningModule", orphanRemoval=true)
     */
    private $translations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="badges")
     */
    private $users;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getBadge(): ?string
    {
        return $this->badge;
    }

    public function setBadge(string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @return Collection|LearningModuleTranslation[]
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(LearningModuleTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setLearningModule($this);
        }

        return $this;
    }

    public function removeTranslation(LearningModuleTranslation $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
            // set the owning side to null (unless already changed)
            if ($translation->getLearningModule() === $this) {
                $translation->setLearningModule(null);
            }
        }

        return $this;
    }

    public function getTitle(Language $language)
    {
        foreach($this->getTranslations() AS $translation) {
            if($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getTitle();//change this line if needed when copied
            }
        }
    }

    public function getDescription(Language $language)
    {
        foreach($this->getTranslations() AS $translation) {
            if($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getDescription();//change this line if needed when copied
            }
        }
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addBadge($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeBadge($this);
        }

        return $this;
    }
}
