<?php

namespace Dios\System\Page\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

/**
 * Implements checking the maintenance mode of the website.
 */
class MaintenanceMode
{
    /**
     * The default template of the page of maintenance mode.
     *
     * @var string
     */
    const DEFAULT_EMPLATE = 'diossystem-page::maintenance-mode';

    /**
     * A template of the page of maintenance mode.
     *
     * @var string
     */
    const TEMPLATE = 'errors.maintenance-mode';

    /**
     * A status code of the response.
     *
     * @var int
     */
    const STATUS_CODE = 503;

    /**
     * Handles an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! config('app.maintenance_mode', false)) {
            return $next($request);
        }

        return $this->message($request);
    }

    /**
     * Returns a page with a message about the maintenance mode.
     *
     * @param mixed|null $data
     * @return Response
     */
    protected function message($data = null): Response
    {
        return response()
            ->view(static::getPath(), compact('data'), static::STATUS_CODE)
        ;
    }

    /**
     * Returns an actual Blade path to the maintenance mode view (a path
     * to the template).
     *
     * @return string
     */
    protected static function getPath(): string
    {
        return View::exists(static::TEMPLATE)
            ? static::DEFAULT_EMPLATE
            : static::TEMPLATE
        ;
    }
}
