<?php

namespace App\Domains\ProductCatalog\Services\SkuGeneration;

use App\Domains\Core\Exceptions\PlatformRuntimeException;

class IdentifierGenerator
{
    public const MASK_NUMERIC = '#';
    public const MASK_ALPHABETICAL = '@';
    public const MASK_ALPHANUMERIC = '*';

    protected static array $alphabets = [
        self::MASK_NUMERIC => '0123456789',
        self::MASK_ALPHABETICAL => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        self::MASK_ALPHANUMERIC => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
    ];

    public static function isValid(string $mask, string $value): bool
    {
        if (strlen($mask) !== strlen($value)) {
            return false;
        }

        $charMasks = str_split($mask);

        foreach ($charMasks as $i => $charMask) {
            if ( ! str_contains(static::$alphabets[$charMask], $value[$i])) {
                return false;
            }
        }

        return true;
    }

    public function generate(string $mask, string $incrementFrom = null): string
    {
        if ( ! is_null($incrementFrom) && ! static::isValid($mask, $incrementFrom)) {
            throw new PlatformRuntimeException('Invalid current value');
        }

        if (is_null($incrementFrom)) {
            return static::generateInitialForMask($mask);
        }

        $identifier = '';

        $reverseCharMasks = array_reverse(str_split($mask));
        $reverseIncrementFrom = array_reverse(str_split($incrementFrom));

        $carry = 1;
        foreach ($reverseCharMasks as $i => $charMask) {
            [$char, $carry] = static::incrementChar($charMask, $reverseIncrementFrom[$i], $carry);

            $identifier = $char.$identifier;
        }

        if ($carry > 0) {
            throw new PlatformRuntimeException('Cannot generate identifier when incrementing from ['.$incrementFrom.'] for mask ['.$mask.']!');
        }

        return $identifier;
    }

    protected function incrementChar(string $mask, string $char, int $carry): array
    {
        // If constant letter, don't change.
        if ( ! isset(static::$alphabets[$mask])) {
            return [$char, $carry];
        }

        $alphabet = static::$alphabets[$mask];

        $index = strpos($alphabet, $char) + $carry;
        $carry = 0;

        if ($index >= strlen($alphabet)) {
            ++$carry;
            $index = 0;
        }

        return [$alphabet[$index], $carry];
    }

    protected function generateInitialForMask(string $mask): string
    {
        return collect(str_split($mask, 1))
            ->transform(fn (string $charMask) => static::$alphabets[$charMask][0])
            ->implode('');
    }
}
