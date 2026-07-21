<?php

declare(strict_types=1);

namespace Serixa\Admin\Support;

use Serixa\Admin\Exceptions\AdminException;

/**
 * Validates semantic option values against an allow-list, raising the
 * package's own {@see AdminException} (unlike the framework's
 * `AssertsOption`, which throws the generic framework exception).
 *
 * @internal
 */
trait AssertsAdminOption
{
    /**
     * @param list<string> $allowed
     */
    private function assertOption(string $value, array $allowed, string $name): void
    {
        if (! in_array($value, $allowed, true)) {
            throw new AdminException(sprintf(
                'Invalid %s %s "%s". Allowed: %s.',
                (new \ReflectionClass(static::class))->getShortName(),
                $name,
                $value,
                implode(', ', $allowed),
            ));
        }
    }
}
