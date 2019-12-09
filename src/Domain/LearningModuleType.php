<?php
declare(strict_types=1);

namespace App\Domain;

class LearningModuleType
{
    public const SOFT = 'SOFT';
    public const HARD = 'HARD';

    const ALL_OPTIONS = [
        self::SOFT,
        self::HARD
    ];

    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function soft() {
        return new self(self::SOFT);
    }

    public static function hard() {
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

    public static function getAllOptions(): array
    {
        return self::ALL_OPTIONS;
    }
}
