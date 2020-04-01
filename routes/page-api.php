<?php

use Belca\Page\Http\Middlewares\MaintenanceMode;
use Dios\System\Page\Http\Controllers\Website\API\PageController;

Route::get('api/{website_url}', PageController::class)
    ->middleware([MaintenanceMode::class, 'api'])
    ->where(['website_url' => '[A-Za-z0-9-_/]+']) 
    ->name('website.api.page')
;
