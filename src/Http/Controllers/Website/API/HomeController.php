<?php

namespace Dios\System\Page\Http\Controllers\Website\API;

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
     * A link to load the home page.
     *
     * @var string
     */
    protected $link = 'home';

    /**
     * Handles the HTTP-request and returns the HTTP-response in the form of JSON.
     *
     * @param  Request      $request
     * @return Response|Page
     */
    public function __invoke(Request $request)
    {
        /** @var Page|null $page **/
        $page = $this->getPage($this->link);

        /** @var array $data **/
        $data = $this->getPageData(compact('page'));

        return response()->json($data);
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
}
