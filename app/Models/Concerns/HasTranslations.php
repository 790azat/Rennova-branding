<?php

namespace App\Models\Concerns;

/**
 * Content stored in Russian in the regular columns, with English and Armenian
 * versions kept in the `translations` JSON column: {"en": {"title": ...}, "hy": {...}}.
 */
trait HasTranslations
{
    public function initializeHasTranslations(): void
    {
        $this->mergeCasts(['translations' => 'array']);
    }

    public function tr(string $field, ?string $locale = null): mixed
    {
        $locale ??= app()->getLocale();
        $value = $this->translations[$locale][$field] ?? null;

        return filled($value) ? $value : $this->getAttribute($field);
    }
}
