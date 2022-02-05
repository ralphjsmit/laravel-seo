<?php

use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;
use function Spatie\PestPluginTestTime\testTime;

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
        ->assertDontSee('twitter:image');
});

it('can correctly render the Twitter Card summary', function () {
    config()->set('seo.title.suffix', ' | Laravel SEO');
    config()->set('seo.description.fallback', 'Fallback description');
    config()->set('seo.image.fallback', 'images/twitter-1743x1743.jpg');
    config()->set('seo.twitter.@username', 'ralphjsmit');

    get(route('seo.test-plain'))
        ->assertSee('<meta name="twitter:card" content="summary">', false)
        ->assertSee('<meta name="twitter:title" content="Test Plain | Laravel SEO">', false)
        ->assertSee('<meta name="twitter:description" content="Fallback description">', false)
        ->assertSee('<meta name="twitter:image" content="' . secure_url('images/twitter-1743x1743.jpg') . '">', false)
        ->assertSee('<meta name="twitter:site" content="@ralphjsmit">', false);
});

it('can correctly render the Twitter Card summary_large_image', function () {
    config()->set('seo.title.suffix', ' | Laravel SEO');
    config()->set('seo.description.fallback', 'Fallback description');
    config()->set('seo.image.fallback', 'images/twitter-3597x1799.jpg');
    config()->set('seo.twitter.@username', 'ralphjsmit');

    get(route('seo.test-plain'))
        ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
        ->assertSee('<meta name="twitter:title" content="Test Plain | Laravel SEO">', false)
        ->assertSee('<meta name="twitter:description" content="Fallback description">', false)
        ->assertSee('<meta name="twitter:image" content="' . secure_url('images/twitter-3597x1799.jpg') . '">', false)
        ->assertSee('<meta name="twitter:site" content="@ralphjsmit">', false);
});

it('will not render the Twitter Card summary_large_image for too large or small images', function (string $image) {
    config()->set('seo.image.fallback', $image);
    config()->set('seo.twitter.@username', 'ralphjsmit');

    get(route('seo.test-plain'))
        ->assertSee('<meta name="twitter:image" content="' . secure_url($image) . '">', false);
})->with([
    ['images/twitter-72x72.jpg'],
]);

it('can correctly render Twitter Card tags for a post or page', function () {
    config()->set('seo.title.suffix', ' | Laravel SEO');
    config()->set('seo.description.fallback', 'Fallback description');
    config()->set('seo.image.fallback', 'images/foo/test-image.jpg');
    config()->set('seo.site_name', 'My Sitename');

    testTime()->freeze();

    $page = Page::create()->addSEO();

    $page->seo->update([
        'description' => 'My great description, set by the SEO model.',
    ]);

    $page::$overrides = [
        'title' => 'My great title',
        'description' => 'My great description, set by a model on a per-page basis.',
        'type' => 'article',
    ];

    $page->refresh();

    get($url = route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="og:title" content="My great title | Laravel SEO">', false)
        ->assertSee('<meta name="og:description" content="My great description, set by a model on a per-page basis.">', false)
        ->assertSee('<meta name="og:image" content="' . secure_url('images/foo/test-image.jpg') . '">', false)
        ->assertSee('<meta name="og:image:width" content="1451">', false)
        ->assertSee('<meta name="og:image:height" content="258">', false)
        ->assertSee('<meta name="og:url" content="' . $url . '">', false)
        ->assertSee('<meta name="og:site_name" content="My Sitename">', false)
        ->assertSee('<meta name="og:type" content="article">', false)
        ->assertSee('<meta name="article:published_time" content="' . $page->created_at->toIso8601String() . '">', false)
        ->assertSee('<meta name="article:modified_time" content="' . $page->updated_at->toIso8601String() . '">', false);
});

it('can correctly render Twitter Card tags for a post or page with a few additional overrides', function () {
    config()->set('seo.title.suffix', ' | Laravel SEO');
    config()->set('seo.description.fallback', 'Fallback description');
    config()->set('seo.image.fallback', 'images/foo/test-image.jpg');
    config()->set('seo.site_name', 'My Sitename');

    testTime()->freeze();

    $page = Page::create()->addSEO();

    $page->seo->update([
        'description' => 'My great description, set by the SEO model.',
    ]);

    $page::$overrides = [
        'title' => 'My great title',
        'description' => 'My great description, set by a model on a per-page basis.',
        'type' => 'article',
        'published_time' => now()->subDays(2),
        'modified_time' => now()->subDay(),
        'section' => 'Laravel',
        'tags' => [
            'PHP',
            'Laravel',
        ],
    ];

    $page->refresh();

    get($url = route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="og:title" content="My great title | Laravel SEO">', false)
        ->assertSee('<meta name="og:description" content="My great description, set by a model on a per-page basis.">', false)
        ->assertSee('<meta name="og:image" content="' . secure_url('images/foo/test-image.jpg') . '">', false)
        ->assertSee('<meta name="og:image:width" content="1451">', false)
        ->assertSee('<meta name="og:image:height" content="258">', false)
        ->assertSee('<meta name="og:url" content="' . $url . '">', false)
        ->assertSee('<meta name="og:site_name" content="My Sitename">', false)
        ->assertSee('<meta name="og:type" content="article">', false)
        ->assertSee('<meta name="article:published_time" content="' . now()->subDays(2)->toIso8601String() . '">', false)
        ->assertSee('<meta name="article:modified_time" content="' . now()->subDay()->toIso8601String() . '">', false)
        ->assertSee('<meta name="article:section" content="Laravel">', false)
        ->assertSee('<meta name="article:tag" content="PHP">', false)
        ->assertSee('<meta name="article:tag" content="Laravel">', false);
});