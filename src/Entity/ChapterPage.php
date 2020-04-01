<?php

namespace App\Entity;

use App\Domain\Breadcrumb;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
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
     * @var integer $position
     * @Gedmo\SortablePosition()
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="App\Entity\Chapter", inversedBy="pages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chapter;

    public function __construct(int $pageNumber, Chapter $chapter, int $position)
    {
        $this->translations = new ArrayCollection();
        $this->pageNumber = $pageNumber;
        $this->chapter = $chapter;
        $this->position = $position;
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


    public function getTitle(Language $language)
    {
        foreach ($this->getTranslations() as $translation) {
            if ($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getTitle(); //change this line if needed when copied
            }
        }
    }

    public function getContent(Language $language)
    {
        foreach ($this->getTranslations() as $translation) {
            if ($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getContent(); //change this line if needed when copied
            }
        }
    }


    public function getDashboardBreadcrumbs(Language $language): array
    {
        $breadcrumbs = $this->getChapter()->getDashboardBreadcrumbs($language);
        $breadcrumbs[] = new Breadcrumb('Edit page');
        return $breadcrumbs;
    }

    public function getEditBreadcrumbs(Language $language): array
    {
        $breadcrumbs = $this->getChapter()->getEditBreadcrumbs($language);
        $breadcrumbs[] = new Breadcrumb(
            'Page: ' . $this->getTitle($language) . ' (' . $language->getCode() . ') ',
            'edit_page',
            ['module' => $this->getChapter()->getLearningModule()->getId(), 'chapter' => $this->getChapter()->getId()]
        );
        return $breadcrumbs;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
