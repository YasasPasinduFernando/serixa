<?php

declare(strict_types=1);

namespace Serixa\Firebase\Contracts;

use Serixa\Firebase\NotificationMessage;
use Serixa\Firebase\NotificationResult;

/**
 * Shared outbound notification contract for push, SMS, and chat adapters.
 */
interface Notifier
{
    public function send(NotificationMessage $message): NotificationResult;
}
