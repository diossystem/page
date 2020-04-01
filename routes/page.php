<?php

use Dios\System\Page\Http\Middlewares\MaintenanceMode;
use Dios\System\Page\Http\Controllers\Website\PageController;

Route::get('{website_url}', PageController::class)
    ->middleware([MaintenanceMode::class, 'web'])
    ->where(['website_url' => '[A-Za-z0-9-_/]+'])
    ->name('website.page')
;
