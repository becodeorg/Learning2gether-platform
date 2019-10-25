<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LearningModuleTranslationsRepository")
 */
class LearningModuleTranslation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LearningModule", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $learningModule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language;

    public function __construct(Language $language, string $title, LearningModule $learningModule, string $description)
    {
        $this->learningModule = $learningModule;
        $this->title = $title;
        $this->description = $description;
        $this->language = $language;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLearningModule(): LearningModule
    {
        return $this->learningModule;
    }

    public function setLearningModule(LearningModule $learningModule): self
    {
        $this->learningModule = $learningModule;

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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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
}
