<?php

namespace App\Domains\Core\Services\Serializers;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Brick\Money\MoneyContainer;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MoneyNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private static $supportedTypes = [
        Money::class => true,
    ];

    /**
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = []): string
    {
        if ( ! $object instanceof Money) {
            throw new InvalidArgumentException('The object must be a "'.Money::class.'" object.');
        }

        return json_encode([
            'amount' => $object->getMinorAmount()->toInt(),
            'currency' => $object->getCurrency()->getCurrencyCode(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof MoneyContainer;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): Money
    {
        if ('' === $data || null === $data) {
            throw new NotNormalizableValueException(
                'The data is either an empty string or null, you should pass a string that can be parsed with'.
                ' the passed format or a valid Money encoded object.'
            );
        }

        $jsonData = json_decode($data, true);

        try {
            return Money::ofMinor($jsonData['amount'], $jsonData['currency']);
        } catch (UnknownCurrencyException $e) {
            throw new NotNormalizableValueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return isset(self::$supportedTypes[$type]);
    }
}
