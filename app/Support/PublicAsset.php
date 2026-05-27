<?php

namespace App\Support;

class PublicAsset
{
    public static function url(?string $path, ?string $fallback = null): ?string
    {
        if (! $path) {
            return $fallback;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        self::ensurePublicCopy($path);

        return asset('storage/'.$path);
    }

    private static function ensurePublicCopy(string $path): void
    {
        $relativePath = ltrim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
        $source = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$relativePath);
        $target = public_path('storage'.DIRECTORY_SEPARATOR.$relativePath);

        if (! file_exists($source) || file_exists($target)) {
            return;
        }

        $directory = dirname($target);

        if (! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        copy($source, $target);
    }
}
