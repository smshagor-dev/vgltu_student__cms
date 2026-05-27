<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageCompressor
{
    public static function storeUploadedFile(
        UploadedFile $file,
        string $directory,
        string $disk = 'public',
        int $targetKilobytes = 220,
        int $maxWidth = 1600
    ): string {
        if (! self::canCompress($file)) {
            return $file->store($directory, $disk);
        }

        $binary = self::compressToWebpBinary($file, $targetKilobytes, $maxWidth);

        if ($binary === null) {
            return $file->store($directory, $disk);
        }

        $path = trim($directory, '/').'/'.Str::uuid().'.webp';
        Storage::disk($disk)->put($path, $binary);

        return $path;
    }

    public static function storeInPublicPath(
        UploadedFile $file,
        string $directory,
        int $targetKilobytes = 220,
        int $maxWidth = 1600
    ): string {
        $directory = trim($directory, '/');
        $fullDirectory = public_path($directory);

        if (! is_dir($fullDirectory)) {
            mkdir($fullDirectory, 0775, true);
        }

        if (! self::canCompress($file)) {
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
            $file->move($fullDirectory, $filename);

            return $filename;
        }

        $binary = self::compressToWebpBinary($file, $targetKilobytes, $maxWidth);

        if ($binary === null) {
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
            $file->move($fullDirectory, $filename);

            return $filename;
        }

        $filename = Str::uuid().'.webp';
        file_put_contents($fullDirectory.DIRECTORY_SEPARATOR.$filename, $binary);

        return $filename;
    }

    public static function canCompress(UploadedFile $file): bool
    {
        if (! function_exists('imagewebp') || ! function_exists('getimagesize')) {
            return false;
        }

        return in_array($file->getMimeType(), [
            'image/gif',
            'image/jpeg',
            'image/png',
            'image/webp',
        ], true);
    }

    private static function compressToWebpBinary(
        UploadedFile $file,
        int $targetKilobytes,
        int $maxWidth
    ): ?string {
        [$width, $height] = getimagesize($file->getPathname()) ?: [0, 0];

        if (! $width || ! $height) {
            return null;
        }

        $image = self::createImageResource($file);

        if (! $image) {
            return null;
        }

        $workingImage = self::resizeIfNeeded($image, $width, $height, $maxWidth);

        if (function_exists('imagepalettetotruecolor')) {
            imagepalettetotruecolor($workingImage);
        }

        imagealphablending($workingImage, true);
        imagesavealpha($workingImage, true);

        $targetBytes = $targetKilobytes * 1024;
        $binary = null;

        foreach ([82, 76, 70, 64, 58, 52, 46, 40] as $quality) {
            $candidate = self::encodeWebp($workingImage, $quality);

            if ($candidate === null) {
                continue;
            }

            $binary = $candidate;

            if (strlen($candidate) <= $targetBytes) {
                break;
            }
        }

        if ($workingImage !== $image) {
            imagedestroy($workingImage);
        }

        imagedestroy($image);

        return $binary;
    }

    private static function createImageResource(UploadedFile $file)
    {
        return match ($file->getMimeType()) {
            'image/gif' => function_exists('imagecreatefromgif') ? @imagecreatefromgif($file->getPathname()) : null,
            'image/jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($file->getPathname()) : null,
            'image/png' => function_exists('imagecreatefrompng') ? @imagecreatefrompng($file->getPathname()) : null,
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($file->getPathname()) : null,
            default => null,
        };
    }

    private static function resizeIfNeeded($image, int $width, int $height, int $maxWidth)
    {
        if ($width <= $maxWidth) {
            return $image;
        }

        $newWidth = $maxWidth;
        $newHeight = (int) round(($height / $width) * $newWidth);

        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);

        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        return $canvas;
    }

    private static function encodeWebp($image, int $quality): ?string
    {
        ob_start();
        $result = imagewebp($image, null, $quality);
        $binary = ob_get_clean();

        if (! $result || $binary === false) {
            return null;
        }

        return $binary;
    }
}
