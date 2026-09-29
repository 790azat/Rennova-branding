<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Media extends Model
{
    public const MIMES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    public const MAX_BYTES = 3 * 1024 * 1024;

    protected $fillable = ['mime', 'size', 'data'];

    protected $hidden = ['data'];

    /** Store raw image bytes and return the public URL path. */
    public static function storeBytes(string $bytes): self
    {
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->buffer($bytes);
        if (! isset(self::MIMES[$mime])) {
            throw new InvalidArgumentException(__('Поддерживаются только JPG, PNG и WebP.'));
        }
        if (strlen($bytes) > self::MAX_BYTES) {
            throw new InvalidArgumentException(__('Файл слишком большой (максимум 3 МБ).'));
        }

        return self::create(['mime' => $mime, 'size' => strlen($bytes), 'data' => base64_encode($bytes)]);
    }

    public function url(): string
    {
        return '/media/'.$this->id.'.'.self::MIMES[$this->mime];
    }
}
