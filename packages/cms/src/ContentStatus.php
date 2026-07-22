<?php

declare(strict_types=1);

namespace Serixa\Cms;

enum ContentStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
}
