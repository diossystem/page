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

        $this->loadBaseMigrations();
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

    public function testCreateUsingFill()
    {
        $template = Template::make([
            'code_name' => 'simple_page',
            'title' => 'Title for users of the system',
            'description' => 'The description for users of the system',
            'priority' => -1,

            // It will be null
            'parent_id' => 0,

            // It will be true
            'active' => false,

            // Undefined
            'user_id' => 1,
        ]);

        $this->assertNull($template->code_name);
        $this->assertNull($template->parent_id);
        $this->assertNull($template->user_id);
        $this->assertNull($template->priority);
        $this->assertNull($template->active);

        $template->code_name = 'simple_page';
        $template->save();

        // The 'active' attribute is null,
        // because the model does not get the default values from DB.
        $this->assertNull($template->active);

        // Updates the instance and gets actual values
        /** @var Template $template **/
        $template = Template::find($template->id);

        $this->assertNotNull($template->id);
        $this->assertEquals('simple_page', $template->code_name);
        $this->assertEquals(0, $template->priority);
        $this->assertTrue($template->active);

        $template->priority = -1;
        $template->parent_id = 1;
        $template->active = false;
        $template->save();

        $this->assertEquals(-1, $template->priority);
        $this->assertEquals(1, $template->parent_id);
        $this->assertFalse($template->active);
    }

    public function testParentAndChildrenRelations()
    {
        /** @var Collection|Template[] $parentTemplates **/
        $parentTemplates = Template::where('code_name', 'blog')->get();

        $this->assertEquals(1, $parentTemplates->count());

        /** @var Template $parent **/
        $parent = $parentTemplates->first();

        $this->assertEquals('Blog. List of posts', $parent->title);

        // Tests the Children relation
        $this->assertEquals(7, $parent->children()->count());

        /** @var Template $child **/
        $child = $parent->children()->first();

        // Tests the Parent relation
        $this->assertEquals($child->parent, $parent);
    }

    public function testParentIdScope()
    {
        /** @var Template $blog **/
        $blog = Template::where('code_name', 'blog')->first();

        /** @var Collection|Template[] $children **/
        $children = Template::parentId($blog->id)->get();

        $this->assertEquals(7, $children->count());
    }

    public function testActiveScope()
    {
        /** @var Collection|Template[] $activeTemplates **/
        $activeTemplates = Template::active()->get();

        $this->assertEquals(13, $activeTemplates->count());
    }

    public function testActiveScopeWithFalse()
    {
        /** @var Collection|Template[] $inactiveTemplates **/
        $inactiveTemplates = Template::active(false)->get();

        $this->assertEquals(5, $inactiveTemplates->count());
    }

    public function testNameScope()
    {
        /** @var Collection|Template[] $templates **/
        $templates = Template::name('blog')->get();

        $this->assertEquals(1, $templates->count());
        $this->assertEquals('blog', $templates->first()->code_name);
    }

    public function testNameScopeWithManyNames()
    {
        /** @var Collection|Template[] $templates **/
        $templates = Template::name('blog', 'post')->get();

        $this->assertEquals(2, $templates->count());
        $this->assertEquals('post', $templates->sortByDesc('id')->first()->code_name);
    }

    public function testDelete()
    {
        /** @var int $numberOfDeleted **/
        $numberOfDeleted = Template::where('active', false)->delete();

        $this->assertNotNull($numberOfDeleted);

        // NOTE: The number 5 was defined in TemplatesTableSeeder.php
        $this->assertEquals(5, $numberOfDeleted);
    }

    public function testCreatingPages()
    {

    }

    public function testCreatingAFS()
    {

    }

    public function testGettingPages()
    {

    }

    public function testGettingAFS()
    {
        // all afs
        // active afs
        // inactive afs
        // unused afs
    }

    public function testGettingPagesAndAFS()
    {
        // all pages
        // active pages
        // inactive pages
        // unpublished pages
    }

    public function testGettingActiveTemplates()
    {
        // code...
    }

    public function testUpdatingPagesByTempate()
    {
        // update pages that have one template:
        // change state,
        // update values of afs
    }

    public function testDeletingPages()
    {
        // inactive/unpublished/draft pages
        // active pages
    }

    public function testDeletingAFS()
    {
        // inactive afs,
        // unused afs
    }

    public function testDeletingPagesAndAFS()
    {
        // delete pages of the template and afs of the template
        // delete template
    }
}
