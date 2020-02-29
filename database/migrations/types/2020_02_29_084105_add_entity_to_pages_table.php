<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEntityToPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            /**
             * Тип страницы или тип связанной сущности.
             *
             * Типы страницы предназначены для сайтов-каталогов, интернет-магазинов,
             * и других "собирающих" сайтов,
             * чтобы определять зависимости страниц и загрузку дополнительных данных.
             * Т.е. указание типа отличного от page указывает на наличие связи
             * с другими независимыми данными (могут использоваться независимо
             * от текущей страницы).
             *
             * Например:
             * 1. page - связан только с отображением страницы, т.е. сам с собой;
             * 2. product - связан с отображением конкретного продукта;
             * 3. category - связан с отображением конкретной категории;
             * 4. user - связан с отображением информации о конкретном
             * пользователи;
             * 5. file - связан с отображением информации о конкретном файле и т.д.
             *
             * Тип страницы может быть абсолютно любым, а его логика зависит
             * от наличия соответствующих обработчиков данных.
             * Кроме того, связи моделей могут иметь отношения многие к одному и
             * один к одному, но не многие ко многим.
             */
            $table->string('entity_type')->default('page')->index();

            /**
             * ID связанной сущности.
             *
             * Используется для определения связи с другими сущностями.
             * Поскольку страница не может быть связана сама с собой, то в
             * качестве отсутствия связи указывается null.
             * Также ID связанной сущности может быть заменен на ID другой
             * сущности такого же типа.
             */
            $table->unsignedInteger('entity_id')->nullable()->index();

            $table->index(['entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['entity_type', 'entity_id']);
        });
    }
}
