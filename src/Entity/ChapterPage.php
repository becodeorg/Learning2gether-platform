<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChapterPageRepository")
 */
class ChapterPage
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
    private $pageNumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChapterPageTranslation", mappedBy="chapterPage", orphanRemoval=true,cascade={"persist"})
     */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chapter", inversedBy="pages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chapter;

    public function __construct(int $pageNumber, Chapter $chapter)
    {
        $this->translations = new ArrayCollection();
        $this->pageNumber = $pageNumber;
        $this->chapter = $chapter;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function setPageNumber(int $pageNumber): self
    {
        $this->pageNumber = $pageNumber;

        return $this;
    }

    /**
     * @return Collection|ChapterPageTranslation[]
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(ChapterPageTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setChapterPage($this);
        }

        return $this;
    }

    public function removeTranslation(ChapterPageTranslation $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
            // set the owning side to null (unless already changed)
            if ($translation->getChapterPage() === $this) {
                $translation->setChapterPage(null);
            }
        }

        return $this;
    }

    public function getChapter(): Chapter
    {
        return $this->chapter;
    }
}
