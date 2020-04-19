<?php

namespace Tests\Unit\Models;

use AdditionalFieldsTableSeeder;
use Dios\System\Page\Models\AdditionalField;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdditionalFieldTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(AdditionalFieldsTableSeeder::class);
    }

    public function testStructure()
    {
        /** @var array|string[] $attributes **/
        $attributes = array_keys(AdditionalField::first()->getOriginal());

        $this->assertEquals([
            'id',
            'code_name',
            'title',
            'description',
            'type',
            'active',
        ], $attributes);
    }
}
