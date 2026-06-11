<?php

namespace App\Http\Controllers;

use App\Support\StorageImage;
use Illuminate\Support\Facades\Storage;

class StorageFileController extends Controller
{
    /**
     * Serve files from `storage/app/public` at `/storage/{path}`.
     *
     * This is a safety net when the `public/storage` symlink is missing/broken.
     */
    public function show(string $path)
    {
        $normalized = StorageImage::normalize($path);

        if (! $normalized) {
            abort(404);
        }

        // Resolve on public disk (migrates legacy public/* copies when needed).
        if (! StorageImage::exists($normalized)) {
            abort(404);
        }

        $absolutePath = Storage::disk('public')->path($normalized);

        if (! is_file($absolutePath)) {
            abort(404);
        }

        return response()->file($absolutePath);
    }
}

