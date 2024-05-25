<?php

use function Pest\Laravel\get;

it('can display the sitemap if path is set', function () {
    config()->set('seo.sitemap', '/storage/sitemap.xml');

    get($url = route('seo.test-plain'))
        ->assertSee('<link type="application/xml" rel="sitemap" title="Sitemap" href="/storage/sitemap.xml">', false);
});
