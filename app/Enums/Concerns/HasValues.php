<?php

declare(strict_types=1);

namespace App\Enums\Concerns;

trait HasValues
{
    /**
     * @return array<int, string|int>
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
