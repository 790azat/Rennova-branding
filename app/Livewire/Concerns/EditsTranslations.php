<?php

namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Admin form state for the non-Russian versions of a model's text fields
 * (stored in the model's `translations` JSON column, see HasTranslations).
 */
trait EditsTranslations
{
    public array $tr = [];

    /** @return array<string, string> field => label; list fields are edited one item per line */
    abstract protected function translatableFields(): array;

    protected function translationListFields(): array
    {
        return [];
    }

    protected function translationLocales(): array
    {
        return array_values(array_diff(array_keys(config('rennova.locales')), ['ru']));
    }

    protected function fillTranslations(?Model $model = null): void
    {
        $this->tr = [];
        foreach ($this->translationLocales() as $locale) {
            foreach (array_keys($this->translatableFields()) as $field) {
                $value = $model?->translations[$locale][$field] ?? '';
                $this->tr[$locale][$field] = is_array($value) ? implode("\n", $value) : (string) $value;
            }
        }
    }

    protected function translationsPayload(): array
    {
        $this->validate(['tr.*.*' => 'nullable|string|max:10000']);

        $out = [];
        foreach ($this->translationLocales() as $locale) {
            foreach (array_keys($this->translatableFields()) as $field) {
                $value = trim((string) ($this->tr[$locale][$field] ?? ''));
                if (in_array($field, $this->translationListFields(), true)) {
                    $value = array_values(array_filter(array_map('trim', explode("\n", $value))));
                }
                if (filled($value)) {
                    $out[$locale][$field] = $value;
                }
            }
        }

        return $out;
    }
}
