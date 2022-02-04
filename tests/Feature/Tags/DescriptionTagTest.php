<?php

use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

it('can display the description tag', function () {
    config()->set('seo.fallback_description', 'This property represents the default SEO description of a website.');

    get(route('seo.test-plain'))
        ->assertSee('<meta name="description" content="This property represents the default SEO description of a website.">', false);
});

it('will not display the description tag if there isn\t a description', function () {
    config()->set('seo.fallback_description', null);

    get(route('seo.test-plain'))
        ->assertDontSee('description');
});

it('will display the description if the associated SEO model has a description', function () {
    $page = Page::create()->addSEO();

    $page->seo->update([
        'description' => 'My great description, set by a model on a per-page basis. ', // <-- Notice the space at the end, that one should be trimmed.
    ]);

    $page->refresh();

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="description" content="My great description, set by a model on a per-page basis.">', false);
});
