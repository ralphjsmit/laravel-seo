<?php

use RalphJSmit\Laravel\SEO\Support\AlternateTag;
use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

it('will not display the aternates tags if there isn\'t any alternate', function () {
    get(route('seo.test-plain'))
        ->assertDontSee('alternate');
});

it('will display the alternates links if the associated SEO model has alternates links', function () {
    $page = Page::create();

    $page::$overrides = [
        'alternates' => [
            new AlternateTag(
                hreflang: 'en',
                href: 'https://example.com/en',
            ),
            new AlternateTag(
                hreflang: 'fr',
                href: 'https://example.com/fr',
            ),
        ],
    ];

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<link rel="alternate" href="https://example.com/en" hreflang="en">', false);
});
