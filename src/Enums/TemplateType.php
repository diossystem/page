<?php

namespace Dios\System\Page\Enums;

use Belca\Support\AbstractEnum;

/**
 * Types of templates (as the types of entities).
 */
class TemplateType extends AbstractEnum
{
    const DEFAULT = self::PAGE;

    /**
     * The page
     *
     * The type is used for pages that show data of a page.
     */
    const PAGE = 'page';
}
