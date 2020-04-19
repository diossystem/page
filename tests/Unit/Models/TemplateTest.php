<?php

namespace Tests\Unit\Models;

use TemplatesTableSeeder;
use Dios\System\Page\Models\Template;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(TemplatesTableSeeder::class);
    }

    public function testStructure()
    {
        /** @var array|string[] $attributes **/
        $attributes = array_keys(Template::first()->getOriginal());

        $this->assertEquals([
            'id',
            'code_name',
            'parent_id',
            'title',
            'description',
            'active',
            'priority',
        ], $attributes);
    }
}
