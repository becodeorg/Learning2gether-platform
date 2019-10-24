<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChapterPageTranslationRepository")
 */
class ChapterPageTranslation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language", inversedBy="chapterPageTranslations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $content = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ChapterPage", inversedBy="language")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chapterPage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getChapterPage(): ?ChapterPage
    {
        return $this->chapterPage;
    }

    public function setChapterPage(?ChapterPage $chapterPage): self
    {
        $this->chapterPage = $chapterPage;

        return $this;
    }
}
