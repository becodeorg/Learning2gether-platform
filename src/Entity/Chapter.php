<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChapterRepository")
 */
class Chapter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $chapterNumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChapterTranslation", mappedBy="chapter", orphanRemoval=true)
     */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LearningModule", inversedBy="chapters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $learningModule;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChapterPage", mappedBy="chapter", orphanRemoval=true)
     */
    private $pages;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Quiz", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    public function __construct(int $chapterNumber, LearningModule $learningModule)
    {
        $this->translations = new ArrayCollection();
        $this->pages = new ArrayCollection();
        $this->chapterNumber = $chapterNumber;
        $this->learningModule = $learningModule;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChapterNumber(): int
    {
        return $this->chapterNumber;
    }

    public function setChapterNumber(int $chapterNumber): self
    {
        $this->chapterNumber = $chapterNumber;

        return $this;
    }

    /**
     * @return Collection|ChapterTranslation[]
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(ChapterTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setChapter($this);
        }

        return $this;
    }

    public function removeTranslation(ChapterTranslation $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
            // set the owning side to null (unless already changed)
            if ($translation->getChapter() === $this) {
                $translation->setChapter(null);
            }
        }

        return $this;
    }

    public function getLearningModule(): LearningModule
    {
        return $this->learningModule;
    }

    /**
     * @return Collection|ChapterPage[]
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(ChapterPage $page): self
    {
        if (!$this->pages->contains($page)) {
            $this->pages[] = $page;
        }

        return $this;
    }

    public function removePage(ChapterPage $page): self
    {
        if ($this->pages->contains($page)) {
            $this->pages->removeElement($page);
        }

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }
}
