<?php

use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

it('can display the fallback description tag', function () {
    config()->set('seo.author.fallback', 'Ralph J. Smit');

    get(route('seo.test-plain'))
        ->assertSee('<meta name="author" content="Ralph J. Smit">', false);
});

it('will not display the author tag if there isn\'t a author', function () {
    config()->set('seo.author.fallback', null);

    get(route('seo.test-plain'))
        ->assertDontSee('author');
});

it('will display the author if the associated SEO model has a author', function () {
    $page = Page::create()->addSEO();

    $page->seo->update([
        'author' => 'Article Author ', // <-- Notice the space at the end, that one should be trimmed.
    ]);

    $page->refresh();

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="author" content="Article Author">', false);
});

it('can override the author', function () {
    $page = Page::create()->addSEO();

    $page->seo->update([
        'author' => 'Article Author ', // <-- Notice the space at the end, that one should be trimmed.
    ]);

    $page::$overrides = [
        'author' => 'Overridden author',
    ];

    $page->refresh();

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="author" content="Overridden author">', false);
});
