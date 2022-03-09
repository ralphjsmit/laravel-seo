<?php

use Illuminate\Support\Str;

use function Pest\Laravel\get;

it('can display the Google robots tag and the canonical URL', function () {
    config()->set('seo.canonical_link', true);

    get($url = route('seo.test-plain', ['name' => 'robots']))
        ->assertSee('<meta name="robots" content="max-snippet:-1,max-image-preview:large,max-video-preview:-1">', false)
        ->assertSee('<link rel="canonical" href="' . Str::before($url, '?name') . '">', false);
});

it('cannot display the canonical url if not allowed', function () {
    config()->set('seo.canonical_link', false);

    get($url = route('seo.test-plain', ['name' => 'robots']))
        ->assertSee('<meta name="robots" content="max-snippet:-1,max-image-preview:large,max-video-preview:-1">', false)
        ->assertDontSee('rel="canonical"', false);
});
