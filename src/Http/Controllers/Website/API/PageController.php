<?php

namespace Dios\System\Page\Http\Controllers\Website\API;

use Dios\System\Page\GetsPages;
use Dios\System\Page\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Implements loading of public pages.
 */
class PageController
{
    use GetsPages;

    /**
     * An URL prefix.
     *
     * @var string
     */
    const URL_PREFIX = '';

    /**
     * Handles the HTTP-request and returns the HTTP-response in the form of JSON.
     *
     * @param  Request      $request
     * @param  string       $url
     * @return Response|Page
     */
    public function __invoke(Request $request, string $url)
    {
        $url = $this->getResourceUrl($url);

        /** @var Page $page **/
        $page = $this->getPageOrFail($url);

        /** @var array $data **/
        $data = $this->getPageData(compact('page'));

        return response()->json($data);
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
}
