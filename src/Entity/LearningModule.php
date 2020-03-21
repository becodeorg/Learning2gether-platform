<?php

namespace App\Entity;

use App\Domain\Breadcrumb;
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
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $chapters;

    public function __construct(string $badge = '', string $image = '', string $type = null, bool $isPublished = false)
    {
        if ($type === null) {
            $type = LearningModuleType::hard();
        }

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
        foreach ($this->getTranslations() as $translation) {
            if ($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getTitle(); //change this line if needed when copied
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
        foreach ($this->getTranslations() as $translation) {
            if ($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getDescription(); //change this line if needed when copied
            }
        }
        return 'Error: Language not found';
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
            if ($chapter->getChapterNumber() === 0) {
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

    private function fetchLastChapterNumber(): int
    {
        $lastChapterNumber = 0;
        /** @var Chapter $chapter */
        foreach ($this->chapters as $chapter) {
            if ($chapter->getChapterNumber() > $lastChapterNumber) {
                $lastChapterNumber = $chapter->getChapterNumber();
            }
        }
        return $lastChapterNumber;
    }

    /** @return UserChapter|array[] */
    public function getUserChapters(User $user): array
    {
        $chaptersDone = $user->getProgressByLearningModule($this);

        $foundCurrentChapter = false;

        $listChapters = [];
        foreach ($this->getChapters() as $chapter) {
            $status = isset($chaptersDone[$chapter->getId()]);

            if (!$status && !$foundCurrentChapter) {
                $status = true;
                $foundCurrentChapter = true;
            }

            $listChapters[] = new UserChapter($chapter, $status);
        }

        return $listChapters;
    }

    public function getDashboardBreadcrumbs(Language $language): array
    {
        return [
            new Breadcrumb(
                'Module',
                'dashboard_module',
                ['module' => $this->getId()]
            )
        ];
    }

    public function getEditBreadcrumbs(Language $language): array
    {
        return [
            new Breadcrumb(
                'Edit ' . $this->getTitle($language) . ' (' . $language->getCode() . ') ',
                'edit_module',
                ['module' => $this->getId()]
            )
        ];
    }

    public function getLearnerBreadcrumbs(Language $language): array
    {
        return [
            new Breadcrumb(
                $this->getTitle($language),
                'module',
                ['module' => $this->getId()]
            )
        ];
    }

    public function getForumBreadcrumbs(Language $language, Category $category): array
    {
        return [
            new Breadcrumb(
                $this->getTitle($language),
                'category',
                ['category' => $category->getId()]
            )
        ];
    }
}
