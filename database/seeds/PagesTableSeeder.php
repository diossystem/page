<?php

use Dios\System\Page\Enums\PageState;
use Illuminate\Database\Seeder;
use Dios\System\Page\Models\Page;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Page::class)->create([
            'slug' => 'article-1',
            'link' => 'blog/article-1',
        ]);

        factory(Page::class)->create([
            'slug' => 'article-10',
            'link' => 'blog/article-10',
        ]);

        $parentPage = factory(Page::class)->create([
            'slug' => 'page-1',
            'link' => 'page-1',
            'title' => 'Parent page',
        ]);

        $this->createChildren($parentPage);

        factory(Page::class, 5)->create([
            'state' => PageState::DRAFT,
        ]);

        factory(Page::class, 5)->create([
            'state' => PageState::UNPUBLISHED,
        ]);

        // Will be published
        factory(Page::class, 5)->create([
            'published_at' => new DateTime('tomorrow'),
            'keywords_tag' => 'tomorrow',
        ]);

        factory(Page::class, 10)->create([
            'keywords_tag' => 'other',
        ]);
    }

    protected function createChildren(Page $parent, int $numberOfChildren = 5)
    {
        $parent->children()->createMany(
            factory(Page::class, $numberOfChildren)
                ->make([
                    'title' => 'Child page: ' . $parent->id . now()->format('H:i:s')
                ])
                ->toArray()
        );
    }
}
