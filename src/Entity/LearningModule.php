<?php

namespace App\Entity;

use App\Domain\LearningModuleType;
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
     * @ORM\OrderBy({"chapterNumber" = "ASC"})
     */
    private $chapters;

    public function __construct(string $badge, string $image, string $type, bool $isPublished = false)
    {
        if(is_null($type)) {
            $type = LearningModuleType::hard();
        }

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

    public function getType(): LearningModuleType
    {
        return $this->type;
    }

    public function setType(LearningModuleType $type): void
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

        $flagData['moduleNeededTranslations'] = [];
        $flagData['moduleStatus'] = false;
        foreach ($moduleTranslations as $moduleTranslation) {
            if ($moduleTranslation->getTitle() === '' || $moduleTranslation->getDescription() === '') {
                $flagData['moduleNeededTranslations'][] = $moduleTranslation->getLanguage()->getName();
            }
        }
        if ((count($flagData['moduleNeededTranslations']) - $languageCount) <= -2 ){
            $flagData['moduleStatus'] = true;
        }

        // checking all chapters
        $chapters = $this->getChapters();
        $flagData['chapters'] = [];
        foreach ($chapters as $chapter) {
            $flagData['chapters']['chapterObjects'][$chapter->getId()][] = $chapter;
            $flagData['chapters'][$chapter->getChapterNumber()]['chapterNeededTranslations'] = [];
            $flagData['chapters'][$chapter->getChapterNumber()]['chapterStatus'] = false;
            $chapterTranslations = $chapter->getTranslations();
            foreach ($chapterTranslations as $chapterTranslation) {
                if ($chapterTranslation->getTitle() === '' || $chapterTranslation->getDescription() === '') {
                    $flagData['chapters'][$chapter->getChapterNumber()]['chapterNeededTranslations'][] = $chapterTranslation->getLanguage()->getName();
                }
            }
            if ((count($flagData['chapters'][$chapter->getChapterNumber()]['chapterNeededTranslations']) - $languageCount) <= -2 ){
                $flagData['chapters'][$chapter->getChapterNumber()]['chapterStatus'] = true;
            }

            // checking all pages
            $chapterPages = $chapter->getPages();
            foreach ($chapterPages as $chapterPage) {
                $flagData['chapters'][$chapter->getChapterNumber()]['pages'][$chapterPage->getPageNumber()]['pageNeededTranslations'] = [];
                $flagData['chapters'][$chapter->getChapterNumber()]['pages'][$chapterPage->getPageNumber()]['pageStatus'] = false;
                $chapterPageTranslations = $chapterPage->getTranslations();
                foreach ($chapterPageTranslations as $chapterPageTranslation) {
                    if ($chapterPageTranslation->getTitle() === '' || $chapterPageTranslation->getContent() === '') {
                        $flagData['chapters'][$chapter->getChapterNumber()]['pages'][$chapterPage->getPageNumber()]['pageNeededTranslations'][] = $chapterPageTranslation->getLanguage()->getName();
                    }
                }
                if ((count($flagData['chapters'][$chapter->getChapterNumber()]['pages'][$chapterPage->getPageNumber()]['pageNeededTranslations']) - $languageCount) <= -2 ){
                    $flagData['chapters'][$chapter->getChapterNumber()]['pages'][$chapterPage->getPageNumber()]['pageStatus'] = true;
                }
            }

            // checking all quizzes
            $quiz = $chapter->getQuiz();
            $flagData['chapters'][$chapter->getChapterNumber()]['quiz'] = [];
            $quizQuestions = $quiz->getQuizQuestions();
            foreach ($quizQuestions as $quizQuestion) {
                $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['questionNeededTranslation'] = [];
                $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['questionStatus'] = false;
                $quizQuestionTranslations = $quizQuestion->getTranslations();
                foreach ($quizQuestionTranslations as $quizQuestionTranslation) {
                    if ($quizQuestionTranslation->getTitle() === '') {
                        $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['questionNeededTranslation'][] = $quizQuestionTranslation->getLanguage()->getName();
                    }
                }
                if ((count($flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['questionNeededTranslation']) - $languageCount) <= -2 ){
                    $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['questionStatus'] = true;
                }

                // checking all answers
                $answers = $quizQuestion->getAnswers();
                $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['answers'] = [];
                foreach ($answers as $answer) {
                    $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['answers'][$answer->getId()]['answerNeededTranslations'] = [];
                    $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['answers'][$answer->getId()]['answerStatus'] = false;
                    $answerTranslations = $answer->getTranslations();
                    foreach ($answerTranslations as $answerTranslation) {
                        if ($answerTranslation->getTitle() === '') {
                            $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['answers'][$answer->getId()]['answerNeededTranslations'][] = $answerTranslation->getLanguage()->getName();
                        }
                    }
                    if ((count($flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['answers'][$answer->getId()]['answerNeededTranslations']) - $languageCount) <= -2 ){
                        $flagData['chapters'][$chapter->getChapterNumber()]['quiz']['questions'][$quizQuestion->getQuestionNumber()]['answers'][$answer->getId()]['answerStatus'] = true;
                    }
                }
            }
        }

        // return the humongous array with all the data
        return $flagData;
    }

    /**
     * @return Collection|Chapter[]
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            if($chapter->getChapterNumber() === 0) {
                //we don't have a chapter number yet - create one based on the last chapter number + 1
                $chapter->setChapterNumber($this->fetchLastChapterNumber() + 1);
            }

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

    private function fetchLastChapterNumber() : int
    {
        $lastChapterNumber = 0;
        /** @var Chapter $chapter */
        foreach($this->chapters AS $chapter) {
            if($chapter->getChapterNumber() > $lastChapterNumber) {
                $lastChapterNumber = $chapter->getChapterNumber();
            }
        }
        return $lastChapterNumber;
    }
}