<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InvalidArgumentException;

class MediaController extends Controller
{
    public function show(int $id): Response
    {
        $media = Media::query()->findOrFail($id);

        return response(base64_decode($media->data), 200, [
            'Content-Type' => $media->mime,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    /** Admin image upload: the browser resizes the image and posts it as a data URL. */
    public function store(Request $request): JsonResponse
    {
        $request->validate(['image' => 'required|string|max:6000000']);

        if (! preg_match('#^data:image/[a-z]+;base64,(.+)$#s', $request->string('image'), $m)
            || ($bytes = base64_decode($m[1], true)) === false) {
            return response()->json(['message' => __('Не удалось прочитать изображение.')], 422);
        }

        try {
            $media = Media::storeBytes($bytes);
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['url' => $media->url()]);
    }
}
