<?php

namespace Dios\System\Page\Enums;

use Belca\Support\AbstractEnum;

class PageStatus extends AbstractEnum
{
    const DEFAULT = self::DRAFT;

    /**
     * A published page.
     */
    const PUBLISHED = 'published';

    /**
     * An unpublished page.
     */
    const UNPUBLISHED = 'unpublished';

    /**
     * A draft page.
     */
    const DRAFT  = 'draft';
}
