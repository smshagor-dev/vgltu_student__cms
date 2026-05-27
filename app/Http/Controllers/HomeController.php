<?php

namespace App\Http\Controllers;

use App\Support\PublicSiteData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private const CARD_WIDTH = 1200;
    private const CARD_HEIGHT = 720;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function downloadStudentCard()
    {
        $user = Auth::user();
        $settings = PublicSiteData::shell()['settings'] ?? [];
        $png = $this->buildStudentCardPng($user, $settings);

        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $user->full_name ?: 'student');

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="student-card-' . $safeName . '.png"',
        ]);
    }
    
    public function view()
    {
        return view('user.medical_status'); // Ensure this view exists
    }
    
    public function getMedicalStatus()
    {
        $user = Auth::user();

        if ($user) {
            return response()->json([
                'medical1' => $user->medical1,
                'medical2' => $user->medical2,
                'medical_status' => $user->medical_status
            ]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function updateMedicalStatus(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $field = $request->field;
        $value = $request->value;

        if (!in_array($field, ['medical1', 'medical2'])) {
            return response()->json(['error' => 'Invalid field'], 400);
        }

        $user->$field = $value;

        if ($user->medical1 === 'Yes' && $user->medical2 === 'Yes') {
            $user->medical_status = 'Complete';
        } else {
            $user->medical_status = 'Not Complete';
        }

        $user->save();

        return response()->json(['success' => true, 'new_status' => $user->medical_status]);
    }

    private function buildStudentCardPng($user, array $settings): string
    {
        $image = imagecreatetruecolor(self::CARD_WIDTH, self::CARD_HEIGHT);
        imageantialias($image, true);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $pageBackground = imagecolorallocate($image, 244, 237, 243);
        imagefill($image, 0, 0, $pageBackground);

        $shadowColor = imagecolorallocatealpha($image, 32, 17, 38, 112);
        $this->drawRoundedRectangle($image, 116, 108, 1084, 650, 42, $shadowColor);

        $card = imagecreatetruecolor(960, 520);
        imagealphablending($card, true);
        imagesavealpha($card, true);
        $transparent = imagecolorallocatealpha($card, 0, 0, 0, 127);
        imagefill($card, 0, 0, $transparent);

        $this->drawVerticalGradient($card, 24, 16, 34, 56, 30, 69, 163, 53, 103);
        $cardBorder = imagecolorallocatealpha($card, 255, 255, 255, 104);
        $this->drawRoundedRectangleBorder($card, 0, 0, 959, 519, 40, $cardBorder, 2);
        imagecopy($image, $card, 120, 80, 0, 0, 960, 520);
        imagedestroy($card);

        $white = imagecolorallocate($image, 255, 255, 255);
        $muted = imagecolorallocatealpha($image, 255, 255, 255, 52);
        $mutedSoft = imagecolorallocatealpha($image, 255, 255, 255, 74);
        $glass = imagecolorallocatealpha($image, 255, 255, 255, 108);
        $glassStrong = imagecolorallocatealpha($image, 255, 255, 255, 92);
        $divider = imagecolorallocatealpha($image, 255, 255, 255, 88);

        $regularFont = $this->resolveFontPath([
            'C:\\Windows\\Fonts\\arial.ttf',
            'C:\\Windows\\Fonts\\ARIAL.TTF',
        ]);
        $boldFont = $this->resolveFontPath([
            'C:\\Windows\\Fonts\\arialbd.ttf',
            'C:\\Windows\\Fonts\\ARIALBD.TTF',
        ]) ?: $regularFont;

        $siteName = trim((string) ($settings['site_name'] ?? 'Global Study Gateway')) ?: 'Global Study Gateway';
        $logoSource = $settings['logo_url'] ?? ($settings['favicon_url'] ?? null);

        $this->drawHeaderBrand($image, $siteName, $logoSource, $white, $mutedSoft, $boldFont, $regularFont, $glass);
        $this->drawPhotoBlock($image, $user->photo, strtoupper(mb_substr($user->full_name ?: 'S', 0, 1)), $white, $boldFont, $glassStrong);
        $this->drawProfileText($image, $user, $white, $mutedSoft, $boldFont, $regularFont);
        $this->drawInfoGrid($image, $user, $glass, $muted, $white, $boldFont, $regularFont);

        imagefilledrectangle($image, 176, 598, 1024, 600, $divider);
        $this->drawText(
            $image,
            'Generated from ' . $siteName . ' for student identity reference.',
            16,
            176,
            632,
            $mutedSoft,
            $regularFont
        );

        ob_start();
        imagepng($image);
        $png = ob_get_clean();
        imagedestroy($image);

        return $png ?: '';
    }

    private function drawHeaderBrand($image, string $siteName, ?string $logoSource, int $titleColor, int $subtitleColor, ?string $boldFont, ?string $regularFont, int $badgeColor): void
    {
        $badgeX = 176;
        $badgeY = 128;
        $badgeHeight = 40;
        $iconX = 176;
        $iconY = 188;
        $iconSize = 64;

        $badgeWidth = 214;
        $this->drawRoundedRectangle($image, $badgeX, $badgeY, $badgeX + $badgeWidth, $badgeY + $badgeHeight, 20, $badgeColor);
        $this->drawText($image, 'OFFICIAL STUDENT CARD', 18, 202, 154, $titleColor, $boldFont, 2);

        $iconBg = imagecolorallocatealpha($image, 255, 255, 255, 98);
        $this->drawRoundedRectangle($image, $iconX, $iconY, $iconX + $iconSize, $iconY + $iconSize, 20, $iconBg);

        $logo = $this->createImageFromSource($logoSource);
        if ($logo) {
            imagecopyresampled($image, $logo, $iconX + 9, $iconY + 9, 0, 0, 46, 46, imagesx($logo), imagesy($logo));
            imagedestroy($logo);
        } else {
            $this->drawCenteredText($image, strtoupper(mb_substr($siteName, 0, 1)), 28, $iconX + 32, $iconY + 43, $titleColor, $boldFont);
        }

        $this->drawFittedText($image, $siteName, 30, 258, 230, 240, $titleColor, $boldFont, 20);
        $this->drawText($image, 'Student identity card', 18, 258, 260, $subtitleColor, $regularFont);
    }

    private function drawPhotoBlock($image, ?string $photo, string $initial, int $textColor, ?string $font, int $frameColor): void
    {
        $this->drawRoundedRectangle($image, 176, 286, 464, 496, 30, $frameColor);
        $photoPath = !empty($photo) ? public_path('storage/' . $photo) : null;
        $photoImage = $photoPath && file_exists($photoPath) ? $this->createImageFromSource($photoPath) : null;

        if ($photoImage) {
            imagecopyresampled($image, $photoImage, 194, 304, 0, 0, 252, 174, imagesx($photoImage), imagesy($photoImage));
            imagedestroy($photoImage);
        } else {
            $placeholder = imagecolorallocatealpha($image, 255, 255, 255, 114);
            $this->drawRoundedRectangle($image, 194, 304, 446, 478, 24, $placeholder);
            $this->drawCenteredText($image, $initial, 82, 320, 406, $textColor, $font);
        }

        $border = imagecolorallocatealpha($image, 255, 255, 255, 86);
        $this->drawRoundedRectangleBorder($image, 194, 304, 446, 478, 24, $border, 2);
    }

    private function drawProfileText($image, $user, int $titleColor, int $subtitleColor, ?string $boldFont, ?string $regularFont): void
    {
        $name = $user->full_name ?: 'Student Name';
        $department = $user->department ?: 'Department not available';

        $nameLines = $this->wrapTextToWidth($name, 34, 288, $boldFont);
        $nameLines = array_slice($nameLines, 0, 2);
        $nameY = 542;
        foreach ($nameLines as $index => $line) {
            $this->drawText($image, $line, 34, 176, $nameY + ($index * 40), $titleColor, $boldFont);
        }

        $departmentY = $nameY + (count($nameLines) * 40) + 8;
        $departmentLines = $this->wrapTextToWidth($department, 22, 288, $regularFont);
        $departmentLines = array_slice($departmentLines, 0, 2);

        foreach ($departmentLines as $index => $line) {
            $this->drawText($image, $line, 22, 176, $departmentY + ($index * 28), $subtitleColor, $regularFont);
        }
    }

    private function drawInfoGrid($image, $user, int $boxColor, int $labelColor, int $valueColor, ?string $boldFont, ?string $regularFont): void
    {
        $cards = [
            ['EMAIL', $user->email ?: 'N/A', 528, 232],
            ['MOBILE NUMBER', $user->mobile_number ?: 'N/A', 780, 232],
            ['ROOM NUMBER', $user->room_number ?: 'N/A', 528, 396],
            ['DATE OF BIRTH', $user->date_of_birth ?: 'N/A', 780, 396],
        ];

        foreach ($cards as [$label, $value, $x, $y]) {
            $this->drawRoundedRectangle($image, $x, $y, $x + 204, $y + 134, 24, $boxColor);
            $border = imagecolorallocatealpha($image, 255, 255, 255, 104);
            $this->drawRoundedRectangleBorder($image, $x, $y, $x + 204, $y + 134, 24, $border, 1);
            $this->drawText($image, $label, 14, $x + 22, $y + 32, $labelColor, $boldFont, 1);
            $this->drawMultilineValue($image, (string) $value, $x + 22, $y + 68, 160, 24, $valueColor, $regularFont);
        }
    }

    private function drawMultilineValue($image, string $text, int $x, int $y, int $maxWidth, int $lineHeight, int $color, ?string $font): void
    {
        $fontSize = 22;
        $lines = $this->wrapTextToWidth($text, $fontSize, $maxWidth, $font);

        while (count($lines) > 3 && $fontSize > 16) {
            $fontSize -= 2;
            $lines = $this->wrapTextToWidth($text, $fontSize, $maxWidth, $font);
        }

        $lines = array_slice($lines, 0, 3);
        foreach ($lines as $index => $line) {
            $this->drawText($image, $line, $fontSize, $x, $y + ($index * $lineHeight), $color, $font);
        }
    }

    private function drawFittedText($image, string $text, int $initialSize, int $x, int $y, int $maxWidth, int $color, ?string $font, int $minSize = 16): void
    {
        $size = $initialSize;
        while ($size > $minSize && $this->measureTextWidth($text, $size, $font) > $maxWidth) {
            $size--;
        }

        $this->drawText($image, $text, $size, $x, $y, $color, $font);
    }

    private function drawText($image, string $text, int $size, int $x, int $y, int $color, ?string $font, int $letterSpacing = 0): void
    {
        if ($font && function_exists('imagettftext')) {
            if ($letterSpacing <= 0) {
                imagettftext($image, $size, 0, $x, $y, $color, $font, $text);
                return;
            }

            $offsetX = $x;
            foreach (mb_str_split($text) as $character) {
                imagettftext($image, $size, 0, $offsetX, $y, $color, $font, $character);
                $offsetX += $this->measureTextWidth($character, $size, $font) + $letterSpacing;
            }
            return;
        }

        imagestring($image, 5, $x, max(0, $y - 15), $text, $color);
    }

    private function drawCenteredText($image, string $text, int $size, int $centerX, int $baselineY, int $color, ?string $font): void
    {
        $width = $this->measureTextWidth($text, $size, $font);
        $this->drawText($image, $text, $size, (int) ($centerX - ($width / 2)), $baselineY, $color, $font);
    }

    private function wrapTextToWidth(string $text, int $size, int $maxWidth, ?string $font): array
    {
        $text = trim(preg_replace('/\s+/', ' ', $text));
        if ($text === '') {
            return [''];
        }

        $words = preg_split('/\s+/', $text) ?: [$text];
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $candidate = $current === '' ? $word : $current . ' ' . $word;
            if ($this->measureTextWidth($candidate, $size, $font) <= $maxWidth) {
                $current = $candidate;
                continue;
            }

            if ($current !== '') {
                $lines[] = $current;
            }

            if ($this->measureTextWidth($word, $size, $font) <= $maxWidth) {
                $current = $word;
                continue;
            }

            $segments = $this->breakLongWord($word, $size, $maxWidth, $font);
            $lines = array_merge($lines, array_slice($segments, 0, -1));
            $current = end($segments) ?: '';
        }

        if ($current !== '') {
            $lines[] = $current;
        }

        return $lines ?: [''];
    }

    private function breakLongWord(string $word, int $size, int $maxWidth, ?string $font): array
    {
        $segments = [];
        $current = '';

        foreach (mb_str_split($word) as $character) {
            $candidate = $current . $character;
            if ($current !== '' && $this->measureTextWidth($candidate, $size, $font) > $maxWidth) {
                $segments[] = $current;
                $current = $character;
                continue;
            }

            $current = $candidate;
        }

        if ($current !== '') {
            $segments[] = $current;
        }

        return $segments ?: [$word];
    }

    private function measureTextWidth(string $text, int $size, ?string $font): int
    {
        if ($font && function_exists('imagettfbbox')) {
            $box = imagettfbbox($size, 0, $font, $text);
            return (int) abs($box[2] - $box[0]);
        }

        return imagefontwidth(5) * strlen($text);
    }

    private function drawVerticalGradient($image, int $r1, int $g1, int $b1, int $r2, int $g2, int $b2, int $r3, int $g3, int $b3): void
    {
        $width = imagesx($image);
        $height = imagesy($image);

        for ($y = 0; $y < $height; $y++) {
            $ratio = $height > 1 ? $y / ($height - 1) : 0;
            if ($ratio < 0.55) {
                $local = $ratio / 0.55;
                $red = (int) round($r1 + (($r2 - $r1) * $local));
                $green = (int) round($g1 + (($g2 - $g1) * $local));
                $blue = (int) round($b1 + (($b2 - $b1) * $local));
            } else {
                $local = ($ratio - 0.55) / 0.45;
                $red = (int) round($r2 + (($r3 - $r2) * $local));
                $green = (int) round($g2 + (($g3 - $g2) * $local));
                $blue = (int) round($b2 + (($b3 - $b2) * $local));
            }
            $color = imagecolorallocate($image, $red, $green, $blue);
            imageline($image, 0, $y, $width, $y, $color);
        }
    }

    private function drawRoundedRectangle($image, int $x1, int $y1, int $x2, int $y2, int $radius, int $color): void
    {
        imagefilledrectangle($image, $x1 + $radius, $y1, $x2 - $radius, $y2, $color);
        imagefilledrectangle($image, $x1, $y1 + $radius, $x2, $y2 - $radius, $color);
        imagefilledellipse($image, $x1 + $radius, $y1 + $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($image, $x2 - $radius, $y1 + $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($image, $x1 + $radius, $y2 - $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($image, $x2 - $radius, $y2 - $radius, $radius * 2, $radius * 2, $color);
    }

    private function drawRoundedRectangleBorder($image, int $x1, int $y1, int $x2, int $y2, int $radius, int $color, int $thickness = 1): void
    {
        imagesetthickness($image, $thickness);
        imageline($image, $x1 + $radius, $y1, $x2 - $radius, $y1, $color);
        imageline($image, $x1 + $radius, $y2, $x2 - $radius, $y2, $color);
        imageline($image, $x1, $y1 + $radius, $x1, $y2 - $radius, $color);
        imageline($image, $x2, $y1 + $radius, $x2, $y2 - $radius, $color);
        imagearc($image, $x1 + $radius, $y1 + $radius, $radius * 2, $radius * 2, 180, 270, $color);
        imagearc($image, $x2 - $radius, $y1 + $radius, $radius * 2, $radius * 2, 270, 360, $color);
        imagearc($image, $x1 + $radius, $y2 - $radius, $radius * 2, $radius * 2, 90, 180, $color);
        imagearc($image, $x2 - $radius, $y2 - $radius, $radius * 2, $radius * 2, 0, 90, $color);
        imagesetthickness($image, 1);
    }

    private function createImageFromSource(?string $source)
    {
        if (! $source) {
            return null;
        }

        if (filter_var($source, FILTER_VALIDATE_URL)) {
            $binary = @file_get_contents($source);
            if ($binary === false) {
                return null;
            }

            return @imagecreatefromstring($binary) ?: null;
        }

        $path = $source;
        if (! file_exists($path) && str_starts_with($source, '/')) {
            $path = public_path(ltrim($source, '/'));
        }

        if (! file_exists($path)) {
            return null;
        }

        $binary = @file_get_contents($path);
        if ($binary === false) {
            return null;
        }

        return @imagecreatefromstring($binary) ?: null;
    }

    private function resolveFontPath(array $paths): ?string
    {
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
