<?php

declare(strict_types=1);

namespace NovaPHP\Component;

use NovaPHP\Contracts\ComponentInterface;
use NovaPHP\Widget\Widget as BaseWidget;

/**
 * @deprecated Use NovaPHP\Widget\Widget. Alias retained for Milestone 1/2 compatibility.
 */
abstract class Component extends BaseWidget implements ComponentInterface
{
}
