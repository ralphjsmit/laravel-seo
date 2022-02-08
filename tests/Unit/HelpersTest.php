<?php

use RalphJSmit\Laravel\SEO\TagManager;
use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

it('can get the TagManager', function () {
    expect(seo())->toBeInstanceOf(TagManager::class);
});

it('can get the TagManager with and without a model', function () {
    expect(seo())->toBeInstanceOf(TagManager::class);

    $page = Page::create();

    expect(seo())->model->toBeNull();
    expect(seo()->for($page))->model->toBe($page);
    expect(seo($page))->model->toBe($page);
    expect(seo(null))->model->toBeNull();
});