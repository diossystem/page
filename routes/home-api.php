<?php

use Belca\Page\Http\Middlewares\MaintenanceMode;
use Dios\System\Page\Http\Controllers\Website\API\HomeController;

Route::get('api', HomeController::class)
    ->middleware([MaintenanceMode::class, 'api'])
    ->name('website.api.home')
;
