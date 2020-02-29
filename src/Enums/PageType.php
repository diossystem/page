<?php

namespace Dios\System\Page\Enums;

use Belca\Support\AbstractEnum;

class PageType extends AbstractEnum
{
    const DEFAULT = self::PAGE;

    /**
     * Страница.
     *
     * Отображает данные указанной страницы.
     */
    const PAGE = 'page';
}
