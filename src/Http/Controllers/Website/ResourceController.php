<?php

namespace Dios\System\Page\Http\Controllers\Website;

use Dios\System\Page\GetsPages;
use Dios\System\Page\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Implements loading of a base page of resources and other pages of the resources.
 * It can be used to implement service pages of the resources: search, filter,
 * categories, category, tags, etc.
 */
class ResourceController extends Controller
{
    use GetsPages;

    /**
     * A base URL of the resources.
     *
     * @var string
     */
    const BASE_URL = 'blog';

    /**
     * An URL prefix.
     *
     * @var string
     */
    const URL_PREFIX = self::BASE_URL . '/';

    /**
     * The default template of the index page of the resources.
     *
     * @var string
     */
    const INDEX_PAGE_TEMPLATE = 'diossystem-page::resources';

    /**
     * The default template of pages of the resources.
     *
     * @var string
     */
    const PAGE_TEMPLATE = 'diossystem-page::page';

    /**
     * Returns a base page of the resources.
     *
     * @param  Request $request
     * @return Response
     *
     * @throws PageNotFoundException
     */
    public function index(Request $request)
    {
        /** @var Page $page **/
        $page = $this->getBasePage();

        /** @var array $data **/
        $data = $this->getBasePageData(compact('page'));

        $template = $this->getBaseTemplate($data);

        return response()
            ->view($template, $data)
        ;
    }

    /**
     * Shows a page of the resource by its slug.
     *
     * @param  Request $request
     * @param  string  $slug
     * @return Response
     *
     * @throws PageNotFoundException
     */
    public function show(Request $request, string $slug)
    {
        $url = $this->getResourceUrl($slug);

        /** @var Page $page **/
        $page = $this->getPageOrFail($url);

        /** @var array $data **/
        $data = $this->getPageData(compact('page'));

        $template = $this->getTemplate($data);

        return response()
            ->view($template, compact('page'))
        ;
    }

    /**
     * Returns the base page of the resources.
     *
     * @return Page
     *
     * @throws PageNotFoundException
     */
    protected function getBasePage()
    {
        return $this->getPageOrFail(
            $this->getBaseUrl()
        );
    }

    /**
     * Returns a base URL of the resources.
     *
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return static::BASE_URL;
    }

    /**
     * Returns an URL of the resource.
     *
     * @param  string $slug
     * @return string
     */
    protected function getResourceUrl(string $slug): string
    {
        return static::URL_PREFIX . $slug;
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
     * Returns data of the base page.
     *
     * @param  mixed|null $data
     * @return array
     */
    protected function getBasePageData($data = null): array
    {
        return $data ?? [];
    }

    /**
     * Returns a path to the template of the index page.
     *
     * @param  mixed|null $data
     * @return string
     */
    protected function getBaseTemplate($data = null): string
    {
        return static::INDEX_PAGE_TEMPLATE;
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
        return static::PAGE_TEMPLATE;
    }
}
