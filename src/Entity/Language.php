<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LanguageRepository")
 */
class Language
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LearningModuleTranslation", mappedBy="language", orphanRemoval=true)
     */
    private $learningModuleTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChapterTranslation", mappedBy="language", orphanRemoval=true)
     */
    private $chapterTranslations;

    public function __construct()
    {
        $this->learningModuleTranslations = new ArrayCollection();
        $this->chapterTranslations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|LearningModuleTranslation[]
     */
    public function getLearningModuleTranslations(): Collection
    {
        return $this->learningModuleTranslations;
    }

    public function addLearningModuleTranslation(LearningModuleTranslation $learningModuleTranslation): self
    {
        if (!$this->learningModuleTranslations->contains($learningModuleTranslation)) {
            $this->learningModuleTranslations[] = $learningModuleTranslation;
            $learningModuleTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removeLearningModuleTranslation(LearningModuleTranslation $learningModuleTranslation): self
    {
        if ($this->learningModuleTranslations->contains($learningModuleTranslation)) {
            $this->learningModuleTranslations->removeElement($learningModuleTranslation);
            // set the owning side to null (unless already changed)
            if ($learningModuleTranslation->getLanguage() === $this) {
                $learningModuleTranslation->setLanguage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ChapterTranslation[]
     */
    public function getChapterTranslations(): Collection
    {
        return $this->chapterTranslations;
    }

    public function addChapterTranslation(ChapterTranslation $chapterTranslation): self
    {
        if (!$this->chapterTranslations->contains($chapterTranslation)) {
            $this->chapterTranslations[] = $chapterTranslation;
            $chapterTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removeChapterTranslation(ChapterTranslation $chapterTranslation): self
    {
        if ($this->chapterTranslations->contains($chapterTranslation)) {
            $this->chapterTranslations->removeElement($chapterTranslation);
            // set the owning side to null (unless already changed)
            if ($chapterTranslation->getLanguage() === $this) {
                $chapterTranslation->setLanguage(null);
            }
        }

        return $this;
    }
}
