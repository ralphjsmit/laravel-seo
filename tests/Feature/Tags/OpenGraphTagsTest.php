<?php

use function Pest\Laravel\get;

beforeEach(function () {
    if ( ! file_exists($dir = public_path('images/foo')) ) {
        mkdir($dir, 0777, true);
    }

    copy(__DIR__ . '/../../Fixtures/images/test-image.jpg', public_path('images/foo/test-image.jpg'));
});

it('will correctly render OpenGraph tags', function () {
    config()->set('seo.title.suffix', ' | Laravel SEO');
    config()->set('seo.description.fallback', 'Fallback description');
    config()->set('seo.image.fallback', 'images/foo/test-image.jpg');
    config()->set('seo.site_name', 'My Sitename');

    get(route('seo.test-plain'))
        ->assertSee('<meta name="og:title" content="Test Plain | Laravel SEO">', false)
        ->assertSee('<meta name="og:description" content="Fallback description">', false)
        ->assertSee('<meta name="og:image" content="' . secure_url('images/foo/test-image.jpg') . '">', false)
        ->assertSee('<meta name="og:image:width" content="1451">', false)
        ->assertSee('<meta name="og:image:height" content="258">', false)
        ->assertSee('<meta name="og:url" content="' . route('seo.test-plain') . '">', false)
        ->assertSee('<meta name="og:site_name" content="My Sitename">', false);
});