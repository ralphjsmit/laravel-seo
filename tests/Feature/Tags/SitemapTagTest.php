<?php

use function Pest\Laravel\get;

it('can display the sitemap if path is set', function () {
    config()->set('seo.sitemap', '/storage/sitemap.xml');

    get($url = route('seo.test-plain'))
        ->assertSee('<link rel="sitemap" href="/storage/sitemap.xml" title="Sitemap" type="application/xml">', false);
});
