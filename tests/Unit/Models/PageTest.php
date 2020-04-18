<?php

namespace Tests\Unit\Models;

use DateTime;
use PagesTableSeeder;
use Dios\System\Page\Models\Page;
use Dios\System\Page\Enums\PageState;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(PagesTableSeeder::class);
    }

    public function testStructure()
    {
        /** @var array|string[] $attributes **/
        $attributes = array_keys(Page::first()->getOriginal());

        $this->assertEquals([
            'id',
            'created_at',
            'updated_at',
            'published_at',
            'state',
            'slug',
            'link',
            'title',
            'subtitle',
            'description',
            'content',
            'description_tag',
            'keywords_tag',
            'template_id',
            'author_id',
            'editor_id',
            'parent_id',
            'priority',
            'important',
        ], $attributes);
    }

    public function testCreateUsingFill()
    {
        $page = Page::make([
            'published_at' => date('now'),
            'title' => 'My title',
            'state' => PageState::PUBLISHED,
            'subtitle' => 'My subtitle',
            'content' => '<p>My content.</p>',
            'description' => 'My discription.',
            'description_tag' => 'The description for search engines',
            'keywords_tag' => 'keywords',
            'link' => 'my-test',

            // They will be null
            'author_id' => 1,
            'editor_id' => 1,
            'parent_id' => 0,
            'template_id' => 0,
        ]);

        $this->assertNull($page->author_id);
        $this->assertNull($page->editor_id);
        $this->assertNull($page->parent_id);
        $this->assertNull($page->template_id);

        $page->author_id = 1;
        $page->save();

        $this->assertNotNull($page->id);
        $this->assertEquals(1, $page->author_id);
    }

    public function testDelete()
    {
        /** @var int $numberOfDeleted **/
        $numberOfDeleted = Page::where('state', PageState::DRAFT)->delete();

        $this->assertNotNull($numberOfDeleted);

        // NOTE: The number 5 was defined in PagesTableSeeder.php
        $this->assertEquals(5, $numberOfDeleted);
    }

    public function testParentAndChildrenRelations()
    {
        /** @var Collection|Page[] $parentPages **/
        $parentPages = Page::where('title', 'Parent page')->get();

        $this->assertEquals(1, $parentPages->count());

        /** @var Page $parent **/
        $parent = $parentPages->first();

        $this->assertEquals('page-1', $parent->link);

        // Tests the Children relation
        $this->assertEquals(5, $parent->children()->count());

        /** @var Page $child **/
        $child = $parent->children()->first();

        // Tests the Parent relation
        $this->assertEquals($child->parent, $parent);
    }

    public function testTemplateRelation()
    {
        //
    }

    public function testRelationOfAdditionalFields()
    {
        //
    }

    public function testStateScope()
    {
        $this->assertEquals(5, Page::state(PageState::DRAFT)->count());
        $this->assertEquals(5, Page::state(PageState::UNPUBLISHED)->count());
        $this->assertGreaterThanOrEqual(10, Page::state(PageState::PUBLISHED)->count());
    }

    public function testSlugScope()
    {
        /** @var Collection|Page[] $pages **/
        $pages = Page::slug('article-1')->get();

        $this->assertEquals(1, $pages->count());

        /** @var Page $page **/
        $page = $pages->first();

        $this->assertEquals('blog/article-1', $page->link);
        $this->assertEquals(PageState::PUBLISHED, $page->state);

        // Using '%' => where slug like 'article-1%'
        /** @var Collection|Page[] $pages **/
        $pages = Page::slug('article-1%')->get();

        $this->assertGreaterThanOrEqual(2, $pages->count());
    }

    public function testLinkScope()
    {
        /** @var Collection|Page[] $pages **/
        $pages = Page::link('BLOG/ARTICLE-1')->get();

        /** @var Page $page **/
        $page = $pages->first();

        $this->assertEquals('article-1', $page->slug);
    }

    public function testActiveScope()
    {
        /** @var Collection|Page[] $pages **/
        $pages = Page::active()->get();

        $this->assertGreaterThanOrEqual(10, $pages->count());
        $this->assertEquals(1, $pages->where('link', 'blog/article-10')->count());
    }

    public function testActiveScopeWithInactivePages()
    {
        /** @var Collection|Page[] $pages **/
        $pages = Page::active(false)->get();

        $this->assertEquals(10, $pages->count());
        $this->assertEquals(0, $pages->where('link', 'blog/article-10')->count());
    }

    public function testActiveTemplateScope()
    {
        //
    }

    public function testPublishedScope()
    {
        /** @var Collection|Page[] $pages **/
        $pages = Page::published(new DateTime())->get();

        $this->assertGreaterThanOrEqual(10, $pages->count());
        $this->assertEquals(10, $pages->where('keywords_tag', 'other')->count());
        $this->assertEquals(0, $pages->where('keywords_tag', 'tomorrow')->count());
    }

    public function testPublishedScopeWithFutureDate()
    {
        /** @var Collection|Page[] $pages **/
        $pages = Page::published(new DateTime('tomorrow'))->get();

        $this->assertGreaterThanOrEqual(15, $pages->count());
        $this->assertEquals(10, $pages->where('keywords_tag', 'other')->count());
        $this->assertEquals(5, $pages->where('keywords_tag', 'tomorrow')->count());
    }

    public function testSeenScope()
    {
        //
    }
}
