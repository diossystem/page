<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            /**
             * Тип шаблона.
             *
             * Тип шаблона определяет функциональное назначение страницы и
             * функции контроллера при работе с шаблоном.
             * По умолчанию в системе используется только один тип - page.
             * Тип page используется для реализации шаблонов страниц (из таблицы
             * 'pages').
             * В системе может быть добавлен любой другой тип шаблона и его
             * реализация, например image, category, product, которые будут
             * использоваться для отображения изображения, категории или продукта.
             */
            $table->string('type')->default('page')->index();
            // TODO Мнимая страница или нулевая страница - страница, которая
            // может иметь все те же данные, что и обычная страница,
            // но поисковая оптимизация и ссылки могут не использоваться.
            // В принципе аналог ДП, за исключением, что также необходимо
            // искать, контролировать статус публикации, правки и т.п.
            // Может иметь связи. Например, для отзывов, которые отображаются
            // на единой странице. Slug может быть опциональным в этом случае.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
