<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CategoryTranslation", mappedBy="category", orphanRemoval=true)
     */
    private $translations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Topic", mappedBy="category", orphanRemoval=true)
     */
    private $topics;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->topics = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|CategoryTranslation[]
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(CategoryTranslation $categoryTranslation): self
    {
        if (!$this->translations->contains($categoryTranslation)) {
            $this->translations[] = $categoryTranslation;
            $categoryTranslation->setCategory($this);
        }

        return $this;
    }

    public function removeTranslation(CategoryTranslation $categoryTranslation): self
    {
        if ($this->translations->contains($categoryTranslation)) {
            $this->translations->removeElement($categoryTranslation);
            // set the owning side to null (unless already changed)
            if ($categoryTranslation->getCategory() === $this) {
                $categoryTranslation->setCategory(null);
            }
        }

        return $this;
    }

    public function getTopics() : Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setCategory($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
            // set the owning side to null (unless already changed)
            if ($topic->getCategory() === $this) {
                $topic->setCategory(null);
            }
        }

        return $this;
    }

    public function getTitle(Language $language)
    {
        foreach($this->getTranslations() AS $translation) {
            if($translation->getLanguage()->getName() === $language->getName()) {
                return $translation->getTitle();//change this line if needed when copied
            }
        }
    }
}