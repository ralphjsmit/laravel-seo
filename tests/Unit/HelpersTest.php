<?php

use RalphJSmit\Laravel\SEO\Support\SEOData;
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

it('can get the TagManager with a SEOData data object', function () {
    expect(seo())->toBeInstanceOf(TagManager::class);

    $SEOData = new SEOData(
        title: 'Awesome News - My Project',
        description: 'Lorem Ipsum',
    );

    expect(seo())->SEOData->toBeNull();
    expect(seo()->for($SEOData))->SEOData->toBe($SEOData);
    expect(seo($SEOData))->SEOData->toBe($SEOData);
    expect(seo(null))->SEOData->toBeNull();
});
