<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Additional fields of templates
 *
 * Дополнительные поля шаблонов создают отношение между дополнительными полями
 * и шаблонами страниц.
 * Они также содержат набор дополнительных настроек для работы с конкретными
 * шаблонами.
 */
class CreateAdditionalFieldsOfTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_fields_of_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('template_id')->index();
            $table->unsignedInteger('additional_field_id')->index();

            /**
             * Ручное управление дополнительного поля.
             *
             * Когда true, поле не отображается автоматически, т.к.
             * предполагается, что поле будет выведено в шаблоне вручную.
             */
            $table->boolean('manual_control')->default(false)->index();

            /**
             * Приоритет отображения.
             *
             * Используется для сортировки отображения дополнительных полей
             * на странице редактирования Страницы.
             */
            $table->smallInteger('priority')->default(0)->index();

            /**
             * Важность дополнительного поля.
             *
             * Важность дополнительного поля может быть использована для
             * загрузки дополнительных полей как при загрузке страницы,
             * так и при загрузке списка страниц.
             * Данное может быть использовано также как primary.
             */
            $table->boolean('important')->default(true);

            /**
             * Первичность дополнительного поля.
             *
             * Свойство поля со значением "true" может быть использовано,
             * например, при загрузке списка страниц, чтобы поля со значением
             * true также были загружены. Т.о. поле будет неотъемлемой частью
             * страницы.
             */
            $table->boolean('primary')->default(false);

            /**
             * Обязательно для заполнения.
             *
             * Если предусмотрено контроллером, то должна выполняться проверка
             * на заполненность данного поля.
             */
            $table->boolean('required')->default(false);

            /**
             * Правила валидации или параметры валидации дополнительного поля.
             *
             * Определяются в БД и/или классом реализующего проверку полей.
             * Используются при сохранении данных страницы.
             * Может хранить параметры для контроллера осуществляющего проверку
             * данных.
             */
            $table->string('rules')->nullable(); // json

            /**
             * Активность дополнительного поля: активно или не активно.
             *
             * Неактивные дополнительные поля могут не отображаться
             * при редактировании страницы и на веб-сайте.
             */
            $table->boolean('active')->default(true)->index();

            // Также могут задаваться начальные данные, хотя это вряд ли.

            /**
             * Для одного шаблона нельзя задать два одинаковых дополнительных поля.
             */
            $table->unique(['template_id', 'additional_field_id'], 'unique_af_of_template');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_fields_of_templates');
    }
}
