<?php

namespace App\Support;

use App\Models\Admin;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleTwoFactorService
{
    private const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    public function generateSecret(int $length = 32): string
    {
        $secret = '';

        for ($i = 0; $i < $length; $i++) {
            $secret .= self::BASE32_ALPHABET[random_int(0, strlen(self::BASE32_ALPHABET) - 1)];
        }

        return $secret;
    }

    public function encryptSecret(string $secret): string
    {
        return Crypt::encryptString($secret);
    }

    public function decryptSecret(?string $encryptedSecret): ?string
    {
        if (! $encryptedSecret) {
            return null;
        }

        return Crypt::decryptString($encryptedSecret);
    }

    public function buildOtpAuthUri(Admin $admin, string $secret): string
    {
        $issuer = config('app.name', 'VGLTU');
        $label = rawurlencode($issuer . ':' . $admin->email);

        return sprintf(
            'otpauth://totp/%s?secret=%s&issuer=%s&algorithm=SHA1&digits=6&period=30',
            $label,
            $secret,
            rawurlencode($issuer)
        );
    }

    public function buildQrCodeUrl(string $otpAuthUri): string
    {
        return 'https://quickchart.io/qr?size=260&text=' . urlencode($otpAuthUri);
    }

    public function verifyCode(string $secret, string $code, int $window = 1): bool
    {
        $normalizedCode = preg_replace('/\s+/', '', $code);

        if (! preg_match('/^\d{6}$/', (string) $normalizedCode)) {
            return false;
        }

        $currentTimeSlice = (int) floor(time() / 30);

        for ($i = -$window; $i <= $window; $i++) {
            if (hash_equals($this->getCode($secret, $currentTimeSlice + $i), $normalizedCode)) {
                return true;
            }
        }

        return false;
    }

    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(Str::random(4) . '-' . Str::random(4));
        }

        return $codes;
    }

    public function hashRecoveryCodes(array $codes): string
    {
        return json_encode(array_map(static fn (string $code) => Hash::make($code), $codes), JSON_THROW_ON_ERROR);
    }

    public function consumeRecoveryCode(Admin $admin, string $inputCode): bool
    {
        $codes = $this->decodeRecoveryCodes($admin->two_factor_recovery_codes);
        $normalizedInput = strtoupper(trim($inputCode));

        foreach ($codes as $index => $hashedCode) {
            if (Hash::check($normalizedInput, $hashedCode)) {
                unset($codes[$index]);
                $admin->two_factor_recovery_codes = json_encode(array_values($codes), JSON_THROW_ON_ERROR);
                $admin->save();

                return true;
            }
        }

        return false;
    }

    public function decodeRecoveryCodes(?string $encodedCodes): array
    {
        if (! $encodedCodes) {
            return [];
        }

        $decoded = json_decode($encodedCodes, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function getCode(string $secret, int $timeSlice): string
    {
        $secretKey = $this->base32Decode($secret);
        $time = pack('N*', 0) . pack('N*', $timeSlice);
        $hash = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $truncatedHash = substr($hash, $offset, 4);
        $value = unpack('N', $truncatedHash)[1] & 0x7FFFFFFF;
        $modulo = 10 ** 6;

        return str_pad((string) ($value % $modulo), 6, '0', STR_PAD_LEFT);
    }

    private function base32Decode(string $secret): string
    {
        $secret = strtoupper($secret);
        $secret = preg_replace('/[^A-Z2-7]/', '', $secret) ?? '';
        $binaryString = '';

        foreach (str_split($secret) as $character) {
            $position = strpos(self::BASE32_ALPHABET, $character);

            if ($position === false) {
                continue;
            }

            $binaryString .= str_pad(decbin($position), 5, '0', STR_PAD_LEFT);
        }

        $result = '';

        foreach (str_split($binaryString, 8) as $byte) {
            if (strlen($byte) === 8) {
                $result .= chr(bindec($byte));
            }
        }

        return $result;
    }
}
