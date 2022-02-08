<?php

use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

beforeEach(function () {
    if ( ! file_exists($dir = public_path('images')) ) {
        mkdir($dir, 0777, true);
    }

    copy(__DIR__ . '/../../Fixtures/images/twitter-72x72.jpg', public_path('images/twitter-72x72.jpg'));
    copy(__DIR__ . '/../../Fixtures/images/twitter-1743x1743.jpg', public_path('images/twitter-1743x1743.jpg'));
    copy(__DIR__ . '/../../Fixtures/images/twitter-4721x4721.jpg', public_path('images/twitter-4721x4721.jpg'));
    copy(__DIR__ . '/../../Fixtures/images/twitter-3597x1799.jpg', public_path('images/twitter-3597x1799.jpg'));
});

it('can correctly render the Twitter Card summary without the image', function () {
    config()->set('seo.title.suffix', ' | Laravel SEO');
    config()->set('seo.description.fallback', 'Fallback description');
    config()->set('seo.image.fallback', 'images/twitter-1743x1743.jpg');
    config()->set('seo.twitter.@username', 'ralphjsmit');

    // The image should not be loaded, because Twitter doesn't allow a 'generic image that spans multiple pages'.
    get(route('seo.test-plain'))
        ->assertSee('<meta name="twitter:card" content="summary">', false)
        ->assertSee('<meta name="twitter:title" content="Test Plain | Laravel SEO">', false)
        ->assertSee('<meta name="twitter:description" content="Fallback description">', false)
        ->assertDontSee('twitter:image')
        ->assertSee('<meta name="twitter:site" content="@ralphjsmit">', false);
});

it('can correctly render the Twitter Card summary with the image on a Page', function (string $expectedCard, string $imagePath) {
    config()->set('seo.title.suffix', ' | Laravel SEO');
    config()->set('seo.description.fallback', 'Fallback description');

    $page = Page::create();

    $page::$overrides = [
        'title' => 'Test Page',
        'image' => $imagePath,
    ];

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="twitter:card" content="' . $expectedCard . '">', false)
        ->assertSee('<meta name="twitter:title" content="Test Page | Laravel SEO">', false)
        ->assertSee('<meta name="twitter:description" content="Fallback description">', false)
        ->assertSee('<meta name="twitter:image" content="' . secure_url($imagePath) . '">', false)
        ->assertDontSee('twitter:site'); // We should not display an empty '@' username.
})->with([
    ['summary', 'images/twitter-1743x1743.jpg'],
    ['summary_large_image', 'images/twitter-3597x1799.jpg'],
]);

it('will not render the Twitter Card summary_large_image for too large or small images', function (string $imagePath) {
    $page = Page::create();

    $page::$overrides = [
        'title' => 'Test Page',
        'image' => $imagePath,
    ];

    get(route('seo.test-page', ['page' => $page]))
        ->assertDontSee('twitter:image');
})->with([
    ['images/twitter-72x72.jpg'],
    ['images/twitter-4721x4721.jpg'],
]);