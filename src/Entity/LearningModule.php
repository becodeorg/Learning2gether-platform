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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="LearningModuleTranslation", mappedBy="learningModule", orphanRemoval=true ,cascade={"persist"})
     */
    private $translations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chapter", mappedBy="learningModule", orphanRemoval=true ,cascade={"persist"})
     */
    private $chapters;

    public function __construct(string $badge, string $image, string $type, bool $isPublished = false)
    {
        $this->translations = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->badge = $badge;
        $this->image = $image;
        $this->type = $type;
        $this->isPublished = $isPublished;
        //default for isPublished is set to false
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

    /**
     * @return string
     */
    public function getBadge(): string
    {
        return $this->badge;
    }

    public function setBadge(string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
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

    /**
     * @param Language $language
     * @return string
     */
    public function getTitle(Language $language): string
    {
        foreach ($this->getTranslations() AS $translation) {
            if ($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getTitle();//change this line if needed when copied
            }
        }
        return 'Error: Language not found';
    }

    /**
     * @return Collection
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    /**
     * @param Language $language
     * @return string
     */
    public function getDescription(Language $language): string
    {
        foreach ($this->getTranslations() AS $translation) {
            if ($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getDescription();//change this line if needed when copied
            }
        }
        return 'Error: Language not found';
    }

    public function flagPage(): array
    {
        //function to check the module in order to show it requires more content before publishing
        // returns an array of integers based on how many translations are filled in
        // also returns a bool whether or not the module can be published (2 translations minimum)

        // empty array for storing error
        $flagData = [];
        $flagData['moduleNeededTranslations'] = [];
        $flagData['chapterNeededTranslations'] = [];
        $flagData['chapterPageNeededTranslations'] = [];
        $flagData['quizQuestionNeededTranslations'] = [];


        // checking all learning module translations
        $moduleTranslations = $this->getTranslations();
        $flagData['moduleTranslation'] = count($moduleTranslations);
        foreach ($moduleTranslations as $moduleTranslation) {
            if ($moduleTranslation->getTitle() === '' || $moduleTranslation->getDescription() === '') {
                --$flagData['moduleTranslation'];
                $flagData['moduleNeededTranslations'][] = $moduleTranslation->getLanguage()->getName();
            }
        }

        //fetching all chapters
        $chapters = $this->getChapters();

        // checking all the chapter's titles and descriptions
        foreach ($chapters as $chapter) {
            $chapterTranslations = $chapter->getTranslations();
            $flagData['chapterTranslation'] = count($chapterTranslations);
            foreach ($chapterTranslations as $chapterTranslation) {
                if ($chapterTranslation->getTitle() === '' || $chapterTranslation->getDescription() === '') {
                    --$flagData['chapterTranslation'];
                    $flagData['chapterNeededTranslations'][] = $chapterTranslation->getLanguage()->getName();
                }
            }
            // checking all the pages' translations
            $chapterPages = $chapter->getPages();
            foreach ($chapterPages as $chapterPage) {
                $chapterPageTranslations = $chapterPage->getTranslations();
                $flagData['chapterPageTranslation'] = count($chapterPageTranslations);
                foreach ($chapterPageTranslations as $chapterPageTranslation) {
                    if ($chapterPageTranslation->getTitle() === '' || $chapterPageTranslation->getContent() === '') {
                        --$flagData['chapterPageTranslation'];
                        $flagData['chapterPageNeededTranslations'][] = $chapterPageTranslation->getLanguage()->getName();
                    }
                }
            }

            // checking the question titles
            $quiz = $chapter->getQuiz();
            $quizQuestions = $quiz->getQuizQuestions();
            foreach ($quizQuestions as $quizQuestion) {
                $quizQuestionTranslations = $quizQuestion->getTranslations();
                $flagData['quizQuestionTranslation'] = count($quizQuestionTranslations);
                foreach ($quizQuestionTranslations as $quizQuestionTranslation) {
                    if ($quizQuestionTranslation->getTitle() === '') {
                        --$flagData['quizQuestionTranslation'];
                        $flagData['quizQuestionNeededTranslations'][$quizQuestionTranslation->getQuizQuestion()->getQuestionNumber()][] = $quizQuestionTranslation->getLanguage()->getName();
                    }
                }
            }
        }

        if ($flagData['moduleTranslation'] < 2 || $flagData['chapterTranslation'] < 2 || $flagData['chapterPageTranslation'] < 2 || $flagData['quizQuestionTranslation'] < 2) {
            $flagData['canBePublished'] = false;
            var_dump($flagData);
            return $flagData;
        }

        $flagData['canBePublished'] = true;
        var_dump($flagData);
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