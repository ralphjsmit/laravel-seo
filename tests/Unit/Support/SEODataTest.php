<?php

use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

beforeEach(function () {
    copy(__DIR__ . '/../../Fixtures/images/test-image.jpg', public_path('test-image.jpg'));
});

it('can determine the size of an image', function () {
    $page = Page::create()->addSEO();

    $page->seo->update([
        'image' => 'test-image.jpg',
    ]);

    $SEOData = $page->seo->prepareForUsage();

    $this->assertEquals(1451, $SEOData->imageMeta()->width);
    $this->assertEquals(258, $SEOData->imageMeta()->height);
});