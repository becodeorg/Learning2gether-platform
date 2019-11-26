<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LearningModuleRepository")
 */
class LearningModule
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
    private $isPublished;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $badge;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;
    //link to the LM image on the server (for marketing prettifying purposes)

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;
    //defines the LM type, for example: the LM is for soft skills or hard skills

    /**
     * @ORM\OneToMany(targetEntity="LearningModuleTranslation", mappedBy="learningModule", orphanRemoval=true,cascade={"persist"})
     */
    private $translations;
    // cascade means a modules translations(titles and descriptions) can be inserted to the DB when their module is flushed. -jan

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chapter", mappedBy="learningModule", orphanRemoval=true ,cascade={"persist"})
     */
    private $chapters;

    //default for isPublished is set to false
    public function __construct(string $badge, string $image, string $type, bool $isPublished = false)
    {
        $this->translations = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->badge = $badge;
        $this->image = $image;
        $this->type = $type;
        $this->isPublished = $isPublished;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getIsPublished(): bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getBadge(): string
    {
        return $this->badge;
    }

    public function setBadge(string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function addTranslation(LearningModuleTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setLearningModule($this);
        }
        return $this;
    }

    public function removeTranslation(LearningModuleTranslation $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
            // set the owning side to null (unless already changed)
            if ($translation->getLearningModule() === $this) {
                $translation->setLearningModule(null);
            }
        }
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

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function getDescription(Language $language)
    {
        foreach ($this->getTranslations() AS $translation) {
            if ($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getDescription();//change this line if needed when copied
            }
        }
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters[] = $chapter;
        }
        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->contains($chapter)) {
            $this->chapters->removeElement($chapter);
        }
        return $this;
    }

    public function flagPage(): array
    {
        //function to flag the module in order to show it requires more content before publishing

        // empty array for storing error
        $flagData = [];

        // checking all learning module translations
        $moduleTranslations = $this->getTranslations();
        foreach ($moduleTranslations as $moduleTranslation) {
            $flagData['moduleTranslation'] = false;
            if ($moduleTranslation->getTitle() === '' || $moduleTranslation->getDescription() === '') {
                $flagData['moduleTranslation'] = true;
            }
        }

        //fetching all chapters
        $chapters = $this->getChapters();

        // checking all the chapter's titles and descriptions
        foreach ($chapters as $chapter) {
            $chapterTranslations = $chapter->getTranslations();
            foreach ($chapterTranslations as $chapterTranslation) {
                $flagData['chapterTranslation'] = false;
                if ($chapterTranslation->getTitle() === '' || $chapterTranslation->getDescription() === '') {
                    $flagData['chapterTranslation'] = true;
                }
            }
            // checking all the pages' translations
            $chapterPages = $chapter->getPages();
            foreach ($chapterPages as $chapterPage) {
                $chapterPageTranslations = $chapterPage->getTranslations();
                foreach ($chapterPageTranslations as $chapterPageTranslation) {
                    $flagData['chapterPageTranslation'] = false;
                    if ($chapterPageTranslation->getTitle() === '' || $chapterPageTranslation->getContent() === '') {
                        $flagData['chapterPageTranslation'] = true;
                    }
                }
            }
        }

        // return array of errors
        return $flagData;
    }

    /**
     * @return Collection|Chapter[]
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

}