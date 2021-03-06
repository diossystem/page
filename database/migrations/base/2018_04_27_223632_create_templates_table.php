<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Templates of pages
 *
 * Шаблоны (templates) имеют связь с дополнительными полями и с представлениями
 * страниц (View, The template).
 *
 * Понятие "шаблон" в системе используется в зависимостях данных и в представлении.
 * Чтобы не путаться, понятие "шаблон" будет относится к тому контексту, где
 * описывается.
 * Но, если понятия перекликаются, то шаблоны страниц (представления) будут
 * отмечены как "The templates", а шаблоны, которые касаются таблицы и данных,
 * будут упоминаться как "шаблоны", т.е. просто шаблоны.
 */
class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');

            /**
             * В базе не предполагается хранение шаблона и пути к нему, т.к.
             * шаблоны хранятся по определенному пути, а имя файла включает
             * имя (поле 'name') шаблона.
             * Отсутствие этого файла не вызовет ошибку, а вызовет шаблон по
             * умолчанию или другой шаблон, прописанный в контроллере.
             */

            /**
             * Уникальное имя шаблона.
             *
             * Используется в функциях запроса страниц с данным шаблоном и
             * в поиске представлений страницы (The templates, View Blade).
             * Шаблон должен находиться в опеределнной папке и иметь определенное
             * имя.
             */
            $table->string('code_name')->unique();

            /**
             * Родительский шаблон.
             *
             * Родительский шаблон отвечает за принадлежность страниц и шаблонов
             * друг к другу.
             *
             * Например, шаблон 'service', может принадлежать к одиночному
             * шаблону 'services'.
             * Принадлежность шаблонов может создавать иерархию хлебных крошек
             * и упрощать управление страницами. Например, открывая шаблон
             * "Услуги" может быть открыта страница со списком услуг и
             * ссылкой на редактирование страницы Услуги.
             */
            $table->unsignedInteger('parent_id')->nullable()->index();

             /**
              * Название шаблона.
              *
              * Используется в системе управления.
              */
             $table->string('title');

             /**
              * Описание шаблона.
              *
              * Используется в системе управления для описания назначения
              * шаблона.
              */
             $table->text('description')->nullable();

             /**
              * Активность шаблона.
              *
              * Отвечает за отображение его в списках при создании страницы.
              * Страницы созданные при использовании данного шаблона открываются
              * как надо.
              */
             $table->boolean('active')->default(true)->index();

             /**
              * Приоритет отображения.
              *
              * Для сортировки шаблонов не использующих сортировку по дате.
              * Может использоваться при отображении списка шаблонов
              * в системе.
              */
             $table->smallInteger('priority')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates');
    }
}
