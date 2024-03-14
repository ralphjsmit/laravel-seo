<?php

use RalphJSmit\Laravel\SEO\Models\SEO;
use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\assertDatabaseCount;

beforeEach(function () {
    copy(__DIR__ . '/../../Fixtures/images/test-image.jpg', public_path('test-image.jpg'));
});

it('can determine the size of an image', function () {
    $page = Page::create();

    $page->seo->update([
        'image' => 'test-image.jpg',
    ]);

    $SEOData = $page->seo->prepareForUsage();

    $this->assertEquals(1451, $SEOData->imageMeta()->width);
    $this->assertEquals(258, $SEOData->imageMeta()->height);
});

it('can allow to prepareForUsage without a model in the database', function () {
    $page = Page::create();

    $page->seo->delete();

    $page->refresh();

    $this->assertNull($page->seo->prepareForUsage()->title);

    $page::$overrides = [
        'title' => 'Test Title',
    ];

    $this->assertSame('Test Title', $page->seo->prepareForUsage()->title);

    // Touch the page so that it is saved again. The default SEO model shouldn't be saved, but discarded.
    $page->touch();

    assertDatabaseCount(SEO::class, 0);
});
