<?php

namespace App\Support;

use App\Models\Setting;

/** Messenger links and map coordinates from the admin settings. */
class Contact
{
    public static function digits(?string $phone): string
    {
        return preg_replace('/\D/', '', (string) $phone);
    }

    /** @return array<string, string> network => URL, only for filled settings */
    public static function messengers(): array
    {
        $links = [];
        if ($n = self::digits(Setting::get('whatsapp'))) {
            $links['whatsapp'] = 'https://wa.me/'.$n;
        }
        $telegram = trim((string) Setting::get('telegram'));
        if (str_starts_with($telegram, '+') && ($n = self::digits($telegram))) {
            $links['telegram'] = 'https://t.me/+'.$n; // phone number instead of a username
        } elseif ($u = ltrim($telegram, '@')) {
            $links['telegram'] = 'https://t.me/'.rawurlencode(preg_replace('#^https?://t\.me/#', '', $u));
        }
        if ($n = self::digits(Setting::get('viber'))) {
            $links['viber'] = 'viber://chat?number=%2B'.$n;
        }

        return $links;
    }

    public static function mapEmbedUrl(): ?string
    {
        [$lat, $lng] = [(float) Setting::get('map_lat'), (float) Setting::get('map_lng')];
        if (! $lat || ! $lng) {
            return null;
        }
        $d = 0.006;

        return 'https://www.openstreetmap.org/export/embed.html?bbox='
            .implode('%2C', [$lng - $d, $lat - $d / 2, $lng + $d, $lat + $d / 2]).'&layer=mapnik&marker='.$lat.'%2C'.$lng;
    }

    public static function mapLink(): string
    {
        return 'https://www.openstreetmap.org/?mlat='.Setting::get('map_lat').'&mlon='.Setting::get('map_lng').'#map=17/'
            .Setting::get('map_lat').'/'.Setting::get('map_lng');
    }
}
