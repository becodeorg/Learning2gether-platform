<?php
declare(strict_types=1);

namespace App\Domain;

use App\Entity\Chapter;
use App\Entity\User;
use DomainException;

/*
 * @description This object is responsible for determining the order in
 * which a start chapter is location compared to the Module it is in.
 * */
class ChapterManager
{
    private $chapters;
    private $activeIndex;

    public function __construct(Chapter $currentChapter) {
        $this->chapters = $currentChapter->getLearningModule()->getChapters();

        /** @var Chapter $page */
        foreach ($this->chapters AS $index => $page) {
            if($currentChapter->getId() === $page->getId()) {
                $this->activeIndex = $index;
                return;
            }
        }

        throw new DomainException('Current chapter not found in this list of chapters: position could not be determined.');
    }

    public function previous() :? Chapter {
        if($this->activeIndex === 0) {
            return null;
        }

        return $this->chapters[$this->activeIndex-1];
    }

    public function next() : Chapter {
        if($this->isLast()) {
            throw new FinishedModuleException('No next chapter found');
        }

        return $this->chapters[$this->activeIndex+1];
    }

    public function isLast() : bool {
        return !isset($this->chapters[$this->activeIndex+1]);
    }

    public function current() : Chapter {
        return $this->chapters[$this->activeIndex];
    }
}