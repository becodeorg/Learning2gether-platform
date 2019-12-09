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
    private $chapterNumber = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChapterTranslation", mappedBy="chapter", orphanRemoval=true ,cascade={"persist"})
     */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LearningModule", inversedBy="chapters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $learningModule;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChapterPage", mappedBy="chapter", orphanRemoval=true,cascade={"persist"})
     * @ORM\OrderBy({"pageNumber" = "ASC"})
     *
     */
    private $pages;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Quiz", inversedBy="chapter", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="progress", orphanRemoval=true)
     */
    private $users;

    public function __construct(LearningModule $learningModule)
    {
        $this->translations = new ArrayCollection();
        $this->pages = new ArrayCollection();
        $this->learningModule = $learningModule;
        $this->users = new ArrayCollection();
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

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getTitle(Language $language)
    {
        foreach ($this->getTranslations() AS $translation) {
            if ($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getTitle();//change this line if needed when copied
            }
        }
    }

    public function getTrTitle(string $langCode): ?string
    {
        foreach ($this->getTranslations() AS $translation) {
            if ($translation->getLanguage()->getCode() === $langCode) {
                return $translation->getTitle();//change this line if needed when copied
            }
        }
        return "Not defined";
    }

    public function getDescription(Language $language)
    {
        foreach ($this->getTranslations() AS $translation) {
            if ($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getDescription();//change this line if needed when copied
            }
        }
    }

    public function createNewPage(): ChapterPage
    {
        $pageCount = count($this->getPages());
        return new ChapterPage(++$pageCount, $this);
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
            $user->addProgress($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeProgress($this);
        }

        return $this;
    }

    public function getFirstPage() : ChapterPage
    {
        if(count($this->getPages()) === 0) {
            throw new \DomainException('Chapter does not contain any pages');
        }

        return $this->getPages()[0];
    }
}
