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
    //hash to badger.io badge
    private $badge;

    /**
     * @ORM\Column(type="string", length=255)
     */
    //link to the LM image on the server (for marketing prettifying purposes)
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    //defines the LM type, for example: the LM is for soft skills or hard skills
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="LearningModuleTranslation", mappedBy="learningModule", orphanRemoval=true)
     */
    private $translations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chapter", mappedBy="learningModule", orphanRemoval=true)
     */
    private $chapters;

    //default for isPublished is set to false
    public function __construct(string $badge, string $image, string $type, bool $isPublished=false)
    {
        $this->translations = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->badge = $badge;
        $this->image = $image;
        $this->type = $type;
        $this->isPublished = $isPublished;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsPublished(): bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getBadge(): string
    {
        return $this->badge;
    }

    public function setBadge(string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

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

    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters[] = $chapter;
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->contains($chapter)) {
            $this->chapters->removeElement($chapter);
            }

        return $this;
    }


    //function to flag the module in order to show it requires more content before publishing
    public function flagPage()
    {
        //TODO flesh out this function to do stuff, (that's a separate ticket)
    }
    
}