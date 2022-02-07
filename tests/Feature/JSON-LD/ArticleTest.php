<?php

use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

it('does not render by default the JSON-LD Schema markup: Article', function () {
    get(route('seo.test-plain'))
        ->assertDontSee('"application/ld+json"')
        ->assertDontSee('"@type": "Article"');
});

it('can correctly render the JSON-LD Schema markup: Article', function () {
    $page = Page::create()->addSeo();

    $page::$overrides = [
        'title' => 'Test title',
        'schema' => SchemaCollection::initialize()->addArticle(),
    ];

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('"application/ld+json"');
});

