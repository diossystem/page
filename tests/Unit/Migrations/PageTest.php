<?php

namespace Dios\System\Page\Tests\Unit\Migrations;

use Dios\System\Page\Enums\PageState;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Dios\System\Page\Tests\TestCase;

class PageTest extends TestCase
{
    public function testStructure()
    {
        $columns = Schema::getColumnListing('pages');

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
        ], $columns);
    }

    public function testEmptyTable()
    {
        /** @var int $numberOfPages **/
        $numberOfPages = DB::table('pages')->count();

        $this->assertSame(0, $numberOfPages);
    }

    public function testInsertRowWithDefaultValues()
    {
        DB::table('pages')->insert([
            'title' => 'Test title',
            'author_id' => 1,
        ]);

        /** @var stdClass $page **/
        $page = DB::table('pages')->where('id', 1)->first();

        $this->assertEquals('Test title', $page->title);
        $this->assertEquals(1, $page->author_id);
        $this->assertEquals(PageState::DRAFT, $page->state);
        $this->assertEquals(0, $page->priority);
        $this->assertEquals(0, $page->important); // false
        $this->assertNull($page->editor_id);
        $this->assertNull($page->slug);
        $this->assertNull($page->link);
        $this->assertNull($page->subtitle);
        $this->assertNull($page->content);
        $this->assertNull($page->template_id);
    }

    public function testInsertRowWithAllValues()
    {
        DB::table('pages')->insert([
            'state' => PageState::PUBLISHED,
            'published_at' => date('Y-m-d H:i:s'),
            'slug' => 'test',
            'link' => 'testing/test',
            'title' => 'Test title',
            'subtitle' => 'Test',
            'description' => 'This is a discription...',
            'content' => '<p>My content.</p>',
            'description_tag' => 'Test description',
            'keywords_tag' => 'test, page',
            'template_id' => 1,
            'author_id' => 1,
            'editor_id' => 2,
            'parent_id' => null,
            'priority' => 15,
            'important' => true,
        ]);

        /** @var stdClass $page **/
        $page = DB::table('pages')->where('id', 1)->first();

        $this->assertEquals('Test title', $page->title);
        $this->assertEquals(1, $page->author_id);
        $this->assertEquals(2, $page->editor_id);
        $this->assertEquals(PageState::PUBLISHED, $page->state);
        $this->assertEquals(15, $page->priority);
        $this->assertEquals(1, $page->important); // true
        $this->assertEquals(2, $page->editor_id);
        $this->assertEquals('test', $page->slug);
        $this->assertEquals('testing/test', $page->link);
        $this->assertEquals('Test', $page->subtitle);
        $this->assertEquals('<p>My content.</p>', $page->content);
        $this->assertEquals('test, page', $page->keywords_tag);
        $this->assertEquals('This is a discription...', $page->description);
        $this->assertEquals(1, $page->template_id);
        $this->assertNull($page->parent_id);
    }

    public function testInsertNullWithException()
    {
        $this->expectException(QueryException::class); // or PDOException::class

        DB::table('pages')->insert([
            'title' => null,
            'author_id' => null,
        ]);
    }

    public function testInsertTitleWithoutOtherColumnsWithException()
    {
        $this->expectException(QueryException::class);

        DB::table('pages')->insert([
            'title' => 'Test title',
        ]);
    }

    public function testInsertAuthorWithoutOtherColumnsWithException()
    {
        $this->expectException(QueryException::class);

        DB::table('pages')->insert([
            'author_id' => 1,
        ]);
    }

    public function testInsertUsingWrongTypesWithException()
    {
        $this->expectException(QueryException::class);

        DB::table('pages')->insert([
            'author_id' => 'wrong type',
            'template_id' => 'wrong type',
            'author_id' => 'wrong type',
            'editor_id' => 'wrong type',
            'parent_id' => 'wrong type',
            'priority' => 'wrong type',
            'important' => 'wrong type',
        ]);
    }
}
