<?php

use Illuminate\Support\Str;

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

