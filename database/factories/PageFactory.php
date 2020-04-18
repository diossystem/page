<?php

use Faker\Generator as Faker;
use Dios\System\Page\Enums\PageState;
use Dios\System\Page\Models\Page;
use Illuminate\Support\Str;

$factory->define(Page::class, function (Faker $faker) {
    $slug = $faker->unique()->slug;
    $content = $faker->realText(10000);

    return [
        'state' => PageState::PUBLISHED,
        'published_at' => now(),
        'slug' => $slug,
        'link' => $slug,
        'title' => $faker->sentence,
        'subtitle' => null,
        'description' => Str::limit($content, 1000),
        'content' => $content,
        'description_tag' => Str::limit($content, 160),
        'keywords_tag' => '',
        'template_id' => null,
        'author_id' => 1,
        'editor_id' => 1,
        'parent_id' => null,
        'priority' => 0,
        'important' => false,
    ];
});
