<?php

namespace Dios\System\Page\Enums;

use Belca\Support\AbstractEnum;

/**
 * Types of entities of pages.
 */
class EntityType extends AbstractEnum
{
    const DEFAULT = self::PAGE;

    /**
     * The page
     *
     * Shows data a current page.
     */
    const PAGE = 'page';
}
