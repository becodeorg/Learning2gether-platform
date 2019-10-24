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
     * @ORM\OneToMany(targetEntity="App\Entity\CategoryTranslation", mappedBy="language", orphanRemoval=true)
     */
    private $categoryTranslations;

    public function __construct()
    {
        $this->learningModuleTranslations = new ArrayCollection();
        $this->categoryTranslations = new ArrayCollection();
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
     * @return Collection|CategoryTranslation[]
     */
    public function getCategoryTranslations(): Collection
    {
        return $this->categoryTranslations;
    }

    public function addCategoryTranslation(CategoryTranslation $categoryTranslation): self
    {
        if (!$this->categoryTranslations->contains($categoryTranslation)) {
            $this->categoryTranslations[] = $categoryTranslation;
            $categoryTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removeCategoryTranslation(CategoryTranslation $categoryTranslation): self
    {
        if ($this->categoryTranslations->contains($categoryTranslation)) {
            $this->categoryTranslations->removeElement($categoryTranslation);
            // set the owning side to null (unless already changed)
            if ($categoryTranslation->getLanguage() === $this) {
                $categoryTranslation->setLanguage(null);
            }
        }

        return $this;
    }
}
