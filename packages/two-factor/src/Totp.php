<?php

declare(strict_types=1);

namespace Serixa\TwoFactor;

use Serixa\Exception\InvalidArgumentException;

/**
 * RFC 6238 TOTP (SHA-1, 30s, 6 digits) without external libraries.
 */
final class Totp
{
    public function __construct(
        private readonly string $secret,
        private readonly int $period = 30,
        private readonly int $digits = 6,
    ) {
        if ($secret === '') {
            throw new InvalidArgumentException('TOTP secret cannot be empty.');
        }
        if ($period < 1 || $digits < 4 || $digits > 8) {
            throw new InvalidArgumentException('Invalid TOTP period or digits.');
        }
    }

    public static function generateSecret(int $bytes = 20): string
    {
        return self::base32Encode(random_bytes($bytes));
    }

    public static function make(string $secret, int $period = 30, int $digits = 6): self
    {
        return new self($secret, $period, $digits);
    }

    public function secret(): string
    {
        return $this->secret;
    }

    public function now(?int $timestamp = null): string
    {
        $timestamp ??= time();
        $counter = intdiv($timestamp, $this->period);

        return $this->atCounter($counter);
    }

    public function verify(string $code, ?int $timestamp = null, int $window = 1): bool
    {
        $timestamp ??= time();
        $code = trim($code);
        for ($i = -$window; $i <= $window; ++$i) {
            $counter = intdiv($timestamp, $this->period) + $i;
            if (hash_equals($this->atCounter($counter), $code)) {
                return true;
            }
        }

        return false;
    }

    public function provisioningUri(string $accountName, string $issuer): string
    {
        $label = rawurlencode($issuer . ':' . $accountName);
        $query = http_build_query([
            'secret' => $this->secret,
            'issuer' => $issuer,
            'period' => $this->period,
            'digits' => $this->digits,
            'algorithm' => 'SHA1',
        ]);

        return 'otpauth://totp/' . $label . '?' . $query;
    }

    private function atCounter(int $counter): string
    {
        $secret = self::base32Decode($this->secret);
        $binCounter = pack('N*', 0, $counter);
        $hash = hash_hmac('sha1', $binCounter, $secret, true);
        $offset = ord($hash[19]) & 0x0F;
        $value = (
            ((ord($hash[$offset]) & 0x7F) << 24)
            | ((ord($hash[$offset + 1]) & 0xFF) << 16)
            | ((ord($hash[$offset + 2]) & 0xFF) << 8)
            | (ord($hash[$offset + 3]) & 0xFF)
        );
        $modulo = 10 ** $this->digits;

        return str_pad((string) ($value % $modulo), $this->digits, '0', STR_PAD_LEFT);
    }

    private static function base32Encode(string $data): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $bits = '';
        foreach (str_split($data) as $char) {
            $bits .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }
        $output = '';
        foreach (str_split($bits, 5) as $chunk) {
            if (strlen($chunk) < 5) {
                $chunk = str_pad($chunk, 5, '0');
            }
            $output .= $alphabet[bindec($chunk)];
        }

        return $output;
    }

    private static function base32Decode(string $data): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $data = strtoupper(rtrim($data, '='));
        $bits = '';
        foreach (str_split($data) as $char) {
            $val = strpos($alphabet, $char);
            if ($val === false) {
                continue;
            }
            $bits .= str_pad(decbin($val), 5, '0', STR_PAD_LEFT);
        }
        $output = '';
        foreach (str_split($bits, 8) as $chunk) {
            if (strlen($chunk) === 8) {
                $output .= chr((int) bindec($chunk));
            }
        }

        return $output;
    }
}
