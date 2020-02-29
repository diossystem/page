<?php

namespace Dios\System\Page\Enums;

use Belca\Support\AbstractEnum;

class TemplateType extends AbstractEnum
{
    const DEFAULT = self::PAGE;

    /**
     * The page type.
     *
     * The type is used for pages that showing data of a page.
     */
    const PAGE = 'page';
}
