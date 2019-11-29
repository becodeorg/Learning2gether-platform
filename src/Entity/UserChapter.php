<?php
declare(strict_types=1);

namespace App\Entity;

/**
* This is a simple value class that is return by LearningModule::
 */
class UserChapter
{
    /** @var Chapter */
    private $chapter;

    /** @var bool */
    private $unlocked;

    public function __construct(Chapter $chapter, bool $status)
    {
        $this->chapter = $chapter;
        $this->unlocked = $status;
    }

    public function getChapter(): Chapter
    {
        return $this->chapter;
    }

    public function isUnlocked()
    {
        return $this->unlocked;
    }
}