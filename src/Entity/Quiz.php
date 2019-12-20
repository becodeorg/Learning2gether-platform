<?php

namespace App\Entity;

use App\Domain\Breadcrumb;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 */
class Quiz
{
    const MINIMUM_PERCENTAGE = 80;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizQuestion", mappedBy="quiz", orphanRemoval=true, cascade={"persist"})
     */
    private $quizQuestions;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Chapter", mappedBy="quiz")
     */
    private $chapter;

    public function getChapter() : Chapter
    {
        return $this->chapter;
    }

    public function __construct()
    {
        $this->quizQuestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|QuizQuestion[]
     */
    public function getQuizQuestions(): Collection
    {
        return $this->quizQuestions;
    }

    public function addQuizQuestion(QuizQuestion $quizQuestion): self
    {
        if (!$this->quizQuestions->contains($quizQuestion)) {
            $this->quizQuestions[] = $quizQuestion;
        }

        return $this;
    }

    public function createNewQuestion(): QuizQuestion
    {
        $questionCount = count($this->getQuizQuestions());
        return new QuizQuestion(++$questionCount, $this);
    }

    public function removeQuizQuestion(QuizQuestion $quizQuestion): self
    {
        if ($this->quizQuestions->contains($quizQuestion)) {
            $this->quizQuestions->removeElement($quizQuestion);
        }

        return $this;
    }

    public function getDashboardBreadcrumbs(Language $language) : array
    {
        return $this->getChapter()->getDashboardBreadcrumbs($language);
    }

    public function getEditBreadcrumbs(Language $language) : array
    {
        $breadcrumbs = $this->getChapter()->getEditBreadcrumbs($language);
        $breadcrumbs[] = new Breadcrumb(
            'Edit Quiz',
            'quiz_show',
            ['id' => $this->getId()]
        );

        return $breadcrumbs;
    }

    public function getLearnerBreadcrumbs(Language $language) : array
    {
        $breadcrumbs = $this->getChapter()->getLearnerBreadcrumbs($language);
        $breadcrumbs[] = new Breadcrumb(
            'Quiz',
            'quiz_show',
            ['id' => $this->getId()]
        );

        return $breadcrumbs;
    }
}
