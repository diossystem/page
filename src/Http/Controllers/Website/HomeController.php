<?php

namespace Dios\System\Page\Http\Controllers\Website;

use Dios\System\Page\Models\Page;
use Dios\System\Page\GetsPages;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Implements loading of the home page.
 */
class HomeController
{
    use GetsPages;

    /**
     * The default template of the home page.
     *
     * @var string
     */
    const DEFAULT_TEMPLATE = 'diossystem-page::home';

    /**
     * The default template of the static home page.
     *
     * @var string
     */
    const DEFAULT_STATIC_TEMPLATE = 'diossystem-page::static-home';

    /**
     * A link to load the home page.
     *
     * @var string
     */
    protected $link = 'home';

    /**
     * Handles the HTTP-request and returns the HTTP-response.
     * The response may contain an HTML page.
     *
     * @param  Request   $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        /** @var Page|null $page **/
        $page = $this->getPage($this->link);

        /** @var array $data **/
        $data = $this->getPageData(compact('page'));

        $template = $this->getTemplate($data);

        return response()->view($template, $data);
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
     * Returns data of the home page.
     *
     * @param  mixed|null $data
     * @return array
     */
    protected function getPageData($data = null): array
    {
        return $data ?? [];
    }

    /**
     * Returns a path to a template of the home page. If the 'page' key is empty
     * then returns a path to DEFAULT_STATIC_TEMPLATE, else returns DEFAULT_TEMPLATE.
     *
     * @param  mixed|null $data
     * @return string
     */
    protected function getTemplate($data = null): string
    {
        return isset($data['page'])
            ? static::DEFAULT_TEMPLATE
            : static::DEFAULT_STATIC_TEMPLATE
        ;
    }
}
