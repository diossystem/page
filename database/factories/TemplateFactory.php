<?php

use Faker\Generator as Faker;
use Dios\System\Page\Enums\TemplateType;
use Dios\System\Page\Models\Template;
use Illuminate\Support\Str;

$factory->define(Template::class, function (Faker $faker) {
    return [
        'code_name' => Str::snake($faker->unique()->words(3, true)),
        'parent_id' => null,
        'title' => $faker->unique()->sentence,
        'description' => $faker->realText(1000),
        'active' => true,
        'priority' => 0,
    ];
});
