<?php

use Illuminate\Support\Str;

use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

it('can display the canonical URL if allowed', function () {
    config()->set('seo.canonical_link', true);

    get($url = route('seo.test-plain', ['name' => 'robots']))
        ->assertSee('<link rel="canonical" href="' . Str::before($url, '?name') . '">', false);
});

it('cannot display the canonical url if not allowed', function () {
    config()->set('seo.canonical_link', false);

    get($url = route('seo.test-plain', ['name' => 'robots']))
        ->assertDontSee('rel="canonical"', false);
});

it('can display the model level canonical url if set', function () {
    config()->set('seo.canonical_link', true);

    $page = Page::create();

    $page::$overrides = [
        'canonical_url' => 'https://example.com/canonical/url/test',
    ];

    $page->refresh();

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<link rel="canonical" href="https://example.com/canonical/url/test">', false);
});