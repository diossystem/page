<?php

namespace Dios\System\Page;

use Illuminate\Http\Response;
use Dios\System\Page\Models\Page;
use Dios\System\Page\Exceptions\PageNotFoundException;

/**
 * The trait gets pages of the Page model.
 */
trait GetsPages
{
    /**
     * Returns a Page or thrown an exception when it not exist.
     *
     * @param  string $url
     * @return Page
     *
     * @throws PageNotFoundException
     */
    protected function getPageOrFail(string $url): Page
    {
        /** @var Page|null $page **/
        $page = $this->getPage($url);

        $this->skipOrFail($page, $url);

        return $page;
    }

    /**
     * Returns a page instance by its url.
     *
     * @param  string $url
     * @return Page|null
     */
    protected function getPage(string $url)
    {
        return Page::query()
            ->seen()
            ->link($url)
            ->with($this->getRelations())
            ->first()
        ;
    }

    /**
     * Returns a list of relationships for the model.
     * The relationships may contain callbacks.
     *
     * @return array|string[]|callable[]
     */
    abstract protected function getRelations(): array;

    /**
     * Skips execution when the instance is exist or thrown an exception
     * when it not exist.
     *
     * @param  mixed       $instance
     * @param  string|null $url
     * @param  string|null $message
     * @return void
     *
     * @throws PageNotFoundException
     */
    protected function skipOrFail($instance = null, string $url = null, string $message = null)
    {
        if (! $instance) {
            $this->throwException($url, $message);
        }
    }

    /**
     * Throws an exception.
     *
     * @param string|null $url
     * @param string|null $message
     *
     * @throws PageNotFoundException
     */
    protected function throwException(string $url = null, string $message = null)
    {
        throw (new PageNotFoundException($message))->setUrl($url);
    }
}
