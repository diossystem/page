<?php

use Dios\System\Page\Http\Middlewares\MaintenanceMode;
use Dios\System\Page\Http\Controllers\Website\HomeController;

Route::get('/', HomeController::class)
    ->middleware([MaintenanceMode::class, 'web'])
    ->name('website.home')
;
