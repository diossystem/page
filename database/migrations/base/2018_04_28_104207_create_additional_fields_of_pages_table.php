<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Values of additional fields of pages
 */
class CreateAdditionalFieldsOfPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_fields_of_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id')->index();
            $table->unsignedInteger('additional_field_id')->index();

            /**
             * Сохраненные данные дополнительного поля.
             *
             * Здесь может быть сохранено несколько значений. Например, если
             * нужно сохранить ссылку и ее название, то будет сохранено 2 и
             * более значений: тип значения, значение, название, способ открытия
             * ссылки.
             *
             * Также можно сохранять массивы значений, например, список изображений.
             */
            $table->text('values')->nullable(); // json

            /**
             * Нельзя добавить для одной страницы два одинаковых ДП. Все должно
             * храняться в одном значении.
             */
            $table->unique(['page_id', 'additional_field_id'], 'unique_af_of_page');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_fields_of_pages');
    }
}
