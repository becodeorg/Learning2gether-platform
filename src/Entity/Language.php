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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChapterPageTranslation", mappedBy="language", orphanRemoval=true)
     */
    private $chapterPageTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizQuestionTranslation", mappedBy="language", orphanRemoval=true)
     */
    private $quizQuestionTranslations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizAnswerTranslation", mappedBy="language", orphanRemoval=true)
     */
    private $quizAnswerTranslations;

    public function __construct()
    {
        $this->learningModuleTranslations = new ArrayCollection();
        $this->chapterTranslations = new ArrayCollection();
        $this->chapterPageTranslations = new ArrayCollection();
        $this->quizQuestionTranslations = new ArrayCollection();
        $this->quizAnswerTranslations = new ArrayCollection();
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

    /**
     * @return Collection|ChapterPageTranslation[]
     */
    public function getChapterPageTranslations(): Collection
    {
        return $this->chapterPageTranslations;
    }

    public function addChapterPageTranslation(ChapterPageTranslation $chapterPageTranslation): self
    {
        if (!$this->chapterPageTranslations->contains($chapterPageTranslation)) {
            $this->chapterPageTranslations[] = $chapterPageTranslation;
            $chapterPageTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removeChapterPageTranslation(ChapterPageTranslation $chapterPageTranslation): self
    {
        if ($this->chapterPageTranslations->contains($chapterPageTranslation)) {
            $this->chapterPageTranslations->removeElement($chapterPageTranslation);
            // set the owning side to null (unless already changed)
            if ($chapterPageTranslation->getLanguage() === $this) {
                $chapterPageTranslation->setLanguage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|QuizQuestionTranslation[]
     */
    public function getQuizQuestionTranslations(): Collection
    {
        return $this->quizQuestionTranslations;
    }

    public function addQuizQuestionTranslation(QuizQuestionTranslation $quizQuestionTranslation): self
    {
        if (!$this->quizQuestionTranslations->contains($quizQuestionTranslation)) {
            $this->quizQuestionTranslations[] = $quizQuestionTranslation;
            $quizQuestionTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removeQuizQuestionTranslation(QuizQuestionTranslation $quizQuestionTranslation): self
    {
        if ($this->quizQuestionTranslations->contains($quizQuestionTranslation)) {
            $this->quizQuestionTranslations->removeElement($quizQuestionTranslation);
            // set the owning side to null (unless already changed)
            if ($quizQuestionTranslation->getLanguage() === $this) {
                $quizQuestionTranslation->setLanguage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|QuizAnswerTranslation[]
     */
    public function getQuizAnswerTranslations(): Collection
    {
        return $this->quizAnswerTranslations;
    }

    public function addQuizAnswerTranslation(QuizAnswerTranslation $quizAnswerTranslation): self
    {
        if (!$this->quizAnswerTranslations->contains($quizAnswerTranslation)) {
            $this->quizAnswerTranslations[] = $quizAnswerTranslation;
            $quizAnswerTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removeQuizAnswerTranslation(QuizAnswerTranslation $quizAnswerTranslation): self
    {
        if ($this->quizAnswerTranslations->contains($quizAnswerTranslation)) {
            $this->quizAnswerTranslations->removeElement($quizAnswerTranslation);
            // set the owning side to null (unless already changed)
            if ($quizAnswerTranslation->getLanguage() === $this) {
                $quizAnswerTranslation->setLanguage(null);
            }
        }

        return $this;
    }
}
