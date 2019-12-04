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
     * @ORM\ManyToOne(targetEntity="App\Entity\Language")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language")
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ChapterPage", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chapterPage;

    public function __construct(Language $language,  ChapterPage $chapterPage, string $title = '', string $content ='')
    {
        $this->language = $language;
        $this->title = $title;
        $this->content = $content;
        $this->chapterPage = $chapterPage;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    //a page can be created with just a title but without further content
    public function getContent(): string
    {
        return $this->content;
    }

    //a page can be created with just a title but without further content
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getChapterPage(): ChapterPage
    {
        return $this->chapterPage;
    }

    public function setChapterPage(ChapterPage $chapterPage): self
    {
        $this->chapterPage = $chapterPage;

        return $this;
    }
}