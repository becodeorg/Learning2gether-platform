<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Bool_;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizAnswerRepository")
 */
class QuizAnswer
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
    private $isCorrect;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizAnswerTranslation", mappedBy="quizAnswer", orphanRemoval=true,cascade={"persist"})
     */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuizQuestion", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quizQuestion;

    public function __construct(bool $isCorrect, QuizQuestion $quizQuestion)
    {
        $this->translations = new ArrayCollection();
        $this->isCorrect = $isCorrect;
        $this->quizQuestion = $quizQuestion;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function IsCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): self
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    /**
     * @return Collection|QuizAnswerTranslation[]
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(QuizAnswerTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setQuizAnswer($this);
        }

        return $this;
    }

    public function removeTranslation(QuizAnswerTranslation $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
            // set the owning side to null (unless already changed)
            if ($translation->getQuizAnswer() === $this) {
                $translation->setQuizAnswer(null);
            }
        }

        return $this;
    }

    public function getQuizQuestion(): QuizQuestion
    {
        return $this->quizQuestion;
    }

    public function getTitle(Language $language): ?string
    {
        foreach ($this->getTranslations() AS $translation) {
            if ($translation->getLanguage()->getCode() === $language->getCode()) {
                return $translation->getTitle();//change this line if needed when copied
            }
        }
        return "Not defined";
    }
}
