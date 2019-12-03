<?php
declare(strict_types=1);

namespace App\Domain;

use App\Entity\ChapterPage;

/*
 * @description This object is responsible for determining the order in which a start page is location compared to the chapter it is in.
 * */
class PageManager
{
    private $pages;
    private $activeIndex;

    public function __construct(ChapterPage $currentPage) {
        $this->pages = $currentPage->getChapter()->getPages();

        /** @var Chapter $page */
        foreach ($this->pages AS $index => $page) {
            if($currentPage->getId() === $page->getId()) {
                $this->activeIndex = $index;
                return;
            }
        }

        throw new \DomainException('Current page not found in this list of pages: position could not be determined.');
    }

    public function previous() :? ChapterPage {
        if($this->activeIndex === 0) {
            return null;
        }

        return $this->pages[$this->activeIndex-1];
    }

    public function next() :? ChapterPage {
        if($this->isLast()) {
            return null;
        }

        return $this->pages[$this->activeIndex+1];
    }

    public function isLast() : bool {
        return !isset($this->pages[$this->activeIndex+1]);
    }
}