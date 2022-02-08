<?php

use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

beforeEach(function () {
    if ( ! file_exists($dir = public_path('test')) ) {
        mkdir($dir, 0777, true);
    }

    if ( ! file_exists($dir = storage_path('test')) ) {
        mkdir($dir, 0777, true);
    }

    copy(__DIR__ . '/../../Fixtures/images/test-image.jpg', public_path('test/image.jpg'));
    copy(__DIR__ . '/../../Fixtures/images/test-image.jpg', storage_path('test/image.jpg'));
});

it('will not render the default image if that was disabled', function () {
    config()->set('seo.image.fallback', null);

    get(route('seo.test-plain'))
        ->assertDontSee('name="image"');
});

it('will render the default image', function (string $imagePath) {
    config()->set('seo.image.fallback', $imagePath);

    get(route('seo.test-plain'))
        ->assertSee('<meta name="image" content="' . secure_url($imagePath) . '">', false);
})->with([
    ['public/test/image.jpg'],
    ['/public/test/image.jpg'],
]);

it('will display the image url from a model', function () {
    $page = Page::create();

    get(route('seo.test-page-image', ['imagepage' => $page]))
        ->assertSee('<meta name="image" content="' . secure_url('public/storage/test/image.jpg') . '">', false);
})->skip('Currently almost not testable.');

it('will display the image url if it came from a model', function () {
    $page = Page::create();

    $page->seo->update([
        'image' => 'test/image.jpg',
    ]);

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="image" content="' . secure_url('test/image.jpg') . '">', false);
});

