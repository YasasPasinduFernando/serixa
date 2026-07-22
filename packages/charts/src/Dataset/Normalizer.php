<?php

declare(strict_types=1);

namespace Serixa\Charts\Dataset;

use Generator;
use Serixa\Charts\Exceptions\ChartsException;
use Traversable;

/**
 * Coerces heterogeneous inputs into a list of numeric values.
 *
 * @internal
 */
final class Normalizer
{
    private function __construct()
    {
    }

    /**
     * @param array<mixed>|Traversable<mixed>|string|Generator|object|Arrayable $input
     * @return list<float>
     */
    public static function numbers(mixed $input): array
    {
        $items = self::toList($input);
        $out = [];

        foreach ($items as $item) {
            if ($item === null || $item === '') {
                continue;
            }

            if (is_bool($item)) {
                $out[] = $item ? 1.0 : 0.0;

                continue;
            }

            if (is_int($item) || is_float($item)) {
                $out[] = (float) $item;

                continue;
            }

            if (is_string($item) && is_numeric($item)) {
                $out[] = (float) $item;

                continue;
            }

            if (is_array($item) && array_key_exists('y', $item) && is_numeric($item['y'])) {
                $out[] = (float) $item['y'];

                continue;
            }

            if (is_object($item) && isset($item->y) && is_numeric($item->y)) {
                $out[] = (float) $item->y;

                continue;
            }

            throw new ChartsException(sprintf(
                'Cannot normalize chart value of type %s.',
                get_debug_type($item),
            ));
        }

        return $out;
    }

    /**
     * @param array<mixed>|Traversable<mixed>|string|Generator|object|Arrayable $input
     * @return list<string>
     */
    public static function labels(mixed $input): array
    {
        $items = self::toList($input);
        $out = [];

        foreach ($items as $item) {
            if ($item === null) {
                $out[] = '';

                continue;
            }

            if (is_scalar($item)) {
                $out[] = (string) $item;

                continue;
            }

            if (is_array($item) && array_key_exists('label', $item)) {
                $out[] = (string) $item['label'];

                continue;
            }

            if (is_object($item) && isset($item->label)) {
                $out[] = (string) $item->label;

                continue;
            }

            throw new ChartsException(sprintf(
                'Cannot normalize chart label of type %s.',
                get_debug_type($item),
            ));
        }

        return $out;
    }

    /**
     * @param array<mixed>|Traversable<mixed>|string|Generator|object|Arrayable $input
     * @return list<mixed>
     */
    public static function toList(mixed $input): array
    {
        if (is_string($input)) {
            $decoded = json_decode($input, true);

            if (! is_array($decoded)) {
                throw new ChartsException('Chart JSON string must decode to an array.');
            }

            return array_values($decoded);
        }

        if ($input instanceof Arrayable) {
            return array_values($input->toArray());
        }

        if ($input instanceof Generator || $input instanceof Traversable) {
            return array_values(iterator_to_array($input, false));
        }

        if (is_array($input)) {
            return array_values($input);
        }

        if (is_object($input) && method_exists($input, 'toArray')) {
            /** @var mixed $arr */
            $arr = $input->toArray();

            if (! is_array($arr)) {
                throw new ChartsException('Object toArray() must return an array.');
            }

            return array_values($arr);
        }

        throw new ChartsException(sprintf(
            'Unsupported chart data input of type %s.',
            get_debug_type($input),
        ));
    }
}
