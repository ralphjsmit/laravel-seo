<?php

use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

beforeEach(function () {
    if (! file_exists($dir = public_path('images'))) {
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

it('can correctly render the Twitter Card summary with the image on a Page', function (string $expectedCard, string $imagePath, string $expectedWidth, string $expectedHeight) {
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
        ->assertSee('<meta name="twitter:image:width" content="' . $expectedWidth . '">', false)
        ->assertSee('<meta name="twitter:image:height" content="' . $expectedHeight . '">', false)
        ->assertDontSee('twitter:site'); // We should not display an empty '@' username.
})->with([
    ['summary', 'images/twitter-1743x1743.jpg', '1743', '1743'],
    ['summary_large_image', 'images/twitter-3597x1799.jpg', '3597', '1799'],
]);

it('will not include the widths and heights of Twitter images if the image was overridden using a URL', function (string $expectedCard, string $imagePath, string $expectedWidth, string $expectedHeight) {
    config()->set('seo.title.suffix', ' | Laravel SEO');
    config()->set('seo.description.fallback', 'Fallback description');

    $page = Page::create();

    $page::$overrides = [
        'title' => 'Test Page',
        'image' => secure_url($imagePath),
    ];

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="twitter:card" content="' . $expectedCard . '">', false)
        ->assertSee('<meta name="twitter:title" content="Test Page | Laravel SEO">', false)
        ->assertSee('<meta name="twitter:description" content="Fallback description">', false)
        ->assertSee('<meta name="twitter:image" content="' . secure_url($imagePath) . '">', false)
        ->assertDontSee('<meta name="twitter:image:width" content="' . $expectedWidth . '">', false)
        ->assertDontSee('<meta name="twitter:image:height" content="' . $expectedHeight . '">', false)
        ->assertDontSee('twitter:site'); // We should not display an empty '@' username.
})->with([
    ['summary', 'images/twitter-1743x1743.jpg', '1743', '1743'],
    ['summary_large_image', 'images/twitter-3597x1799.jpg', '3597', '1799'],
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

it('uses openGraphTitle over title', function () {
    config()->set('seo.title.suffix', ' | Laravel SEO');

    $page = Page::create();
    $page::$overrides = [
        'openGraphTitle' => 'My OG title',
    ];
    $page->seo->update([
        'title' => 'My page title',
    ]);

    $page->refresh();

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="twitter:title" content="My OG title | Laravel SEO">', false);
});

it('will escape the title', function () {
    config()->set('seo.title.suffix', ' - A & B');

    $page = Page::create();
    $page->seo->update([
        'title' => 'My page title',
    ]);

    $page->refresh();

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="twitter:title" content="My page title - A &amp; B">', false);
});
