<?php

namespace Dios\System\Page\Http\Controllers\Website;

use Dios\System\Page\GetsPages;
use Dios\System\Page\Models\Page;
use Dios\System\Page\Exceptions\PageNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Implements loading of public pages.
 */
class PageController extends Controller
{
    use GetsPages;

    /**
     * The default template of a page.
     *
     * @var string
     */
    const DEFAULT_TEMPLATE = 'diossystem-page::page';

    /**
     * An URL prefix.
     *
     * @var string
     */
    const URL_PREFIX = '';

    /**
     * Handles the HTTP-request and returns the HTTP-response.
     * The response may contain an HTML page.
     *
     * @param  Request  $request
     * @param  string   $url
     * @return Response
     *
     * @throws PageNotFoundException
     */
    public function __invoke(Request $request, string $url)
    {
        $url = $this->getResourceUrl($url);

        /** @var Page $page **/
        $page = $this->getPageOrFail($url);

        /** @var array $data **/
        $data = $this->getPageData(compact('page'));

        $template = $this->getTemplate($data);

        return response()->view($template, $data);
    }

    /**
     * Returns an URL of the resource.
     *
     * @param  string $slug
     * @return string
     */
    protected function getResourceUrl(string $url)
    {
        return static::URL_PREFIX . $url;
    }

    /**
     * Returns a list of relationships for the model.
     * The relationships may contain callbacks.
     *
     * @return array
     */
    protected function getRelations(): array
    {
        return [
            // Returns only active additional fields
            'afs' => function ($query) {
                $query->active();
            },
        ];
    }

    /**
     * Returns data of the page.
     *
     * @param  mixed|null $data
     * @return array
     */
    protected function getPageData($data = null): array
    {
        return $data ?? [];
    }

    /**
     * Returns a path to a template of the page.
     *
     * @param  mixed|null $data
     * @return string
     */
    protected function getTemplate($data = null): string
    {
        return static::DEFAULT_TEMPLATE;
    }
}
