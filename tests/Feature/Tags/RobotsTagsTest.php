<?php

use Illuminate\Support\Str;

use function Pest\Laravel\get;

it('can display the Google robots tag, canonical URL and sitemap if allowed', function () {
    config()->set('seo.canonical_link', true);
    config()->set('seo.sitemap', '/storage/sitemap.xml');

    get($url = route('seo.test-plain', ['name' => 'robots']))
        ->assertSee('<meta name="robots" content="max-snippet:-1,max-image-preview:large,max-video-preview:-1">', false)
        ->assertSee('<link rel="canonical" href="' . Str::before($url, '?name') . '">', false)
        ->assertSee('<link rel="sitemap" href="/storage/sitemap.xml" type="application/xml" title="Sitemap">', false);
});

it('cannot display the canonical url if not allowed', function () {
    config()->set('seo.canonical_link', false);
    config()->set('seo.sitemap', null);

    get($url = route('seo.test-plain', ['name' => 'robots']))
        ->assertSee('<meta name="robots" content="max-snippet:-1,max-image-preview:large,max-video-preview:-1">', false)
        ->assertDontSee('rel="canonical"', false)
        ->assertDontSee('rel="sitemap"', false);
});

