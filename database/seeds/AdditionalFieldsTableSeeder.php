<?php

use Illuminate\Database\Seeder;
use Dios\System\Page\Models\AdditionalField;

class AdditionalFieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(AdditionalField::class)->create([
            'code_name' => 'map',
            'title' => 'Map',
            'type' => 'map',
        ]);

        factory(AdditionalField::class)->create([
            'code_name' => 'images',
            'title' => 'Images',
            'type' => 'files',
        ]);

        factory(AdditionalField::class)->create([
            'code_name' => 'contacts',
            'title' => 'Contacts',
        ]);

        factory(AdditionalField::class)->create([
            'code_name' => 'sources',
            'title' => 'Sources of a content',
        ]);

        factory(AdditionalField::class)->create([
            'code_name' => 'recommendations',
            'title' => 'Page with services',
            'type' => 'local_pages',
        ]);

        factory(AdditionalField::class, 5)->create([
            'active' => false,
            'description' => 'inactive',
        ]);
    }
}
