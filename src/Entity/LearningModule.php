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
        //function to check the module in order to show it requires more translations before publishing
        $flagData = [];

        // checking all learning module translations
        $moduleTranslations = $this->getTranslations();
        $flagData['moduleNeededTranslations'] = [];
        foreach ($moduleTranslations as $moduleTranslation) {
            if ($moduleTranslation->getTitle() === '' || $moduleTranslation->getDescription() === '') {
                $flagData['moduleNeededTranslations'][] = $moduleTranslation->getLanguage()->getName();
            }
        }

        // checking all chapters
        $chapters = $this->getChapters();
        $flagData['chapters'] = [];
        foreach ($chapters as $chapter) {
            $flagData['chapters'][$chapter->getChapterNumber()]['chapterNeededTranslations'] = [];
            $chapterTranslations = $chapter->getTranslations();
            foreach ($chapterTranslations as $chapterTranslation) {
                if ($chapterTranslation->getTitle() === '' || $chapterTranslation->getDescription() === '') {
                    $flagData['chapters'][$chapter->getChapterNumber()]['chapterNeededTranslations'][] = $chapterTranslation->getLanguage()->getName();
                }
            }

            // checking all pages
            $chapterPages = $chapter->getPages();
            foreach ($chapterPages as $chapterPage) {
                $flagData['chapters'][$chapter->getChapterNumber()]['pages'][$chapterPage->getPageNumber()]['pageNeededTranslations'] = [];
                $chapterPageTranslations = $chapterPage->getTranslations();
                foreach ($chapterPageTranslations as $chapterPageTranslation) {
                    if ($chapterPageTranslation->getTitle() === '' || $chapterPageTranslation->getContent() === '') {
                        $flagData['chapters'][$chapter->getChapterNumber()]['pages'][$chapterPage->getPageNumber()]['pageNeededTranslations'][] = $chapterPageTranslation->getLanguage()->getName();
                    }
                }
            }

            // checking all quizzes
            $quiz = $chapter->getQuiz();
            $flagData['chapters'][$chapter->getChapterNumber()]['quiz'] = [];
            $quizQuestions = $quiz->getQuizQuestions();
            foreach ($quizQuestions as $quizQuestion) {
                $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['questionNeededTranslation'] = [];
                $quizQuestionTranslations = $quizQuestion->getTranslations();
                foreach ($quizQuestionTranslations as $quizQuestionTranslation) {
                    if ($quizQuestionTranslation->getTitle() === '') {
                        $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['questionNeededTranslation'][] = $quizQuestionTranslation->getLanguage()->getName();
                    }
                }

                // checking all answers
                $answers = $quizQuestion->getAnswers();
                $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['answers'] = [];
                foreach ($answers as $answer) {
                    $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['answers'][$answer->getId()] = [];
                    $answerTranslations = $answer->getTranslations();
                    foreach ($answerTranslations as $answerTranslation) {
                        if ($answerTranslation->getTitle() === '') {
                            $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['answers'][$answer->getId()]['answerNeededTranslations'][] = $answerTranslation->getLanguage()->getName();
                        }
                    }
                }
            }

            // TODO add canBePublished bool check
        }

        // return the array with all the data
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