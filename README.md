# The Page component

The Page component for Laravel.

Build your website using the Page. A page is the foundation of all websites.

## Introduction

The package includes basic functions to work with pages of a website:
- getting published pages;
- the maintenance mode;
- the routing (manual setting);
- templates and additional fields of pages;
- the foundation for SEO.

## Installation and setting

You must install the package after installing Laravel.

Install the package using **Composer**.

```bash
composer require "diossystem/page:0.1.*"
```

Use migrations to prepare tables to the package.

```bash
php artisan vendor:publish --tag=page-migrations
```

You may add new or change existing columns, but follow recommendations in the migrations.

### Routes

The package does not include active routes that usually connecting within a service provider of package. You must add appropriate routes in your RouteServiceProvider yourself or add the routes in your route list other way.

#### Basic route list

The package proposes the following name basic routes:
- website.home - to open the homepage of the website;
- website.page - to open other pages of the website;
- website.api.home - to load data of the homepage of the website;
- website.api.page - to load data of other pages of the website.

By default it includes the following controllers of web pages:

**Example: The basic route list of the website**
```php
use Belca\Page\Http\Middlewares\MaintenanceMode;
use Dios\System\Page\Http\Controllers\Website\HomeController;

// It must be at the top in your route list
Route::get('/', HomeController::class)
    ->middleware([MaintenanceMode::class, 'web'])
    ->name('website.home')
;
```

```php
use Dios\System\Page\Http\Controllers\Website\API\HomeController;

// It must be in the middle of the route list or the first in the part of API routes.
Route::get('api', HomeController::class)
    ->middleware([MaintenanceMode::class, 'api'])
    ->name('website.api.home')
;
```

```php
use Dios\System\Page\Http\Controllers\Website\API\PageController;

// It must be after 'website.api.home' in the end of the part of API routes.
Route::get('api/{website_url}', PageController::class)
    ->middleware([MaintenanceMode::class, 'api'])
    ->where(['website_url' => '[A-Za-z0-9-_/]+']) // Allowed symbols for your links
    ->name('website.api.page')
;
```

```php
use Dios\System\Page\Http\Controllers\Website\PageController;

// It must be at the end in your route list
Route::get('{website_url}', PageController::class)
    ->middleware([MaintenanceMode::class, 'web'])
    ->where(['website_url' => '[A-Za-z0-9-_/]+'])
    ->name('website.page')
;
```

Use `MaintenanceMode` to allow or disallow users to see the pages of your website. The other part of the website will be opened.

You may use anyone names of the routes. You may use additional routes to process requests of other pages.

It is better to use a RouteServiceProvider to store the basic routes.

#### Preparing RouteServiceProvider

Prepare your RouteServiceProvider:
1. create basic files to load pages of the website;
1. add the basic routes in the files;
2. add the basic files in your RouteServiceProvider;
3. add own new routes in the files.

At first create the basic files to load pages of the website.

At the third place, you must add in your RouteServiceProvider the basic files to load pages of website.

**Example: Your RouteServiceProvider**
```php
namespace App\Providers;

use Dios\System\Page\Http\Middlewares\MaintenanceMode;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    protected $systemNamespace = 'App\Http\Controllers\System';

    protected $websiteNamespace = 'App\Http\Controllers\Website';

    /**
     * Defines the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapSystemApiRoutes();
        $this->mapApiRoutes();
        $this->mapWebsiteApiRoutes();
        $this->mapSystemRoutes();
        $this->mapWebRoutes();
        $this->mapWebsiteRoutes();
    }

    /**
     * Includes API routes for the application.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'))
        ;
    }

    /**
     * Includes routes for the application. Registers the homepage of the website.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'))
        ;
    }

    /**
     * Includes system API routes.
     *
     * @return void
     */
    protected function mapSystemApiRoutes()
    {
        Route::middleware(['api'])
            ->namespace($this->systemNamespace)
            ->group(base_path('routes/system-api.php'))
        ;
    }

    /**
     * Includes system routes.
     *
     * @return void
     */
    protected function mapSystemRoutes()
    {
        Route::middleware(['web'])
             ->namespace($this->systemNamespace)
             ->group(base_path('routes/system.php'))
        ;
    }

    /**
     * Includes API routes to get data of pages of the website.
     *
     * @return void
     */
    protected function mapWebsiteApiRoutes()
    {
        Route::middleware([MaintenanceMode::class, 'api'])
            ->namespace($this->websiteNamespace)
            ->group(base_path('routes/website-api.php'))
        ;
    }

    /**
     * Includes routes to get pages of the website.
     *
     * @return void
     */
    protected function mapWebsiteRoutes()
    {
        Route::middleware([MaintenanceMode::class, 'web'])
            ->namespace($this->websiteNamespace)
            ->group(base_path('routes/website.php'))
        ;
    }
}
```

You can change this structure by following the recommendations.

- TODO Описать каждый файл, что в него можно добавлять, в каком порядке они должны идти.

Add your own routes to manage your website in 'routes/system-api.php' and 'routes/system.php'.

Добавление своих маршрутов и контроллеров для веб-страниц.

3. Замена базовых шаблонов

### MaintenanceMode

.env : MAINTENANCE_MODE=false
config/app.php : 'maintenance_mode' => env('MAINTENANCE_MODE', false),

## How to use

0. Использование шаблонов и дополнительных полей
1. Как расширить таблицы и модели. Существующие расширения
2. Как расширить классы
3. Как изменить логику работы:
3.1. загрузки страниц,
3.2. поиска шаблонов,
3.3. подгрузки данных,
3.4. выдачи страницы



- Используемые миграции и их расширение вручную или с помощью готовых миграций. Перечисления, константы и их расширение.
- Используемые модели и их отношние
- Расширение моделей, создание новых отношений или изменение существующих
- Загрузка веб-сайта: главная страница и другие страницы веб-сайта. Подключение маршрутов. Роутинги и допустимые URL. Используемые посредники. Переопределение маршрутов и посредников
- API публичных страниц
- Режим обслуживания
- Загрузка данных страницы и связанных данных
- Переопределение логики загрузки главной страницы и страниц веб-сайта. Расширение или переопределение контроллеров
- Используемые представления страниц
