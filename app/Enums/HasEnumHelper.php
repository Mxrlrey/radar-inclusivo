<?php

namespace App\Enums;

trait HasEnumHelper
{
    /**
     * Retorna um array [value => label] para selects no Blade.
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();
    }

    /**
     * Retorna apenas os valores [value1, value2] para validações.
     */
    public static function values(): array
    {
        return collect(self::cases())->pluck('value')->toArray();
    }
}
