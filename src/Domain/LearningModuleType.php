<?php
declare(strict_types=1);

namespace App\Domain;

class LearningModuleType
{
    const SOFT = 'SOFT';
    const HARD = 'HARD';

    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    static public function soft() {
        return new self(self::SOFT);
    }

    static public function hard() {
        return new self(self::HARD);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}