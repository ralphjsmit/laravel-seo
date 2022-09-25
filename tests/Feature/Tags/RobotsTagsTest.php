<?php

use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('can output the robots tag "default" value', function () {
    config()->set('seo.robots.default', 'max-snippet:-1');

    get($url = route('seo.test-plain'))
        ->assertSee('<meta name="robots" content="max-snippet:-1">', false);
});

it('can overwrite the robots tag "default" value with the robots attribute (SEOData)', function () {
    config()->set('seo.robots.default', 'max-snippet:-1');
    config()->set('seo.robots.force_default', false);

    $SEOData = new SEOData(
        robots: 'noindex,nofollow',
    );

    $SEODataOutput = (string) seo($SEOData);

    $this->assertStringContainsString('<meta name="robots" content="noindex,nofollow">', $SEODataOutput);
});

it('cannot overwrite the robots tag "default" value with the robots attribute if "force_default" is set (SEOData)', function () {
    config()->set('seo.robots.default', 'max-snippet:-1');
    config()->set('seo.robots.force_default', true);

    $SEOData = new SEOData(
        robots: 'noindex,nofollow',
    );

    $SEODataOutput = (string) seo($SEOData);

    $this->assertStringContainsString('<meta name="robots" content="max-snippet:-1">', $SEODataOutput);
});

it('can overwrite the robots tag "default" value with the robots attribute (DB Model)', function () {
    config()->set('seo.robots.default', 'max-snippet:-1');

    $page = Page::create();

    $page->seo->update([
        'robots' => 'noindex,nofollow',
    ]);

    $page->refresh();

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="robots" content="noindex,nofollow">', false);
});

it('cannot overwrite the robots tag "default" value with the robots attribute if "force_default" is set (DB Model)', function () {
    config()->set('seo.robots.default', 'max-snippet:-1');
    config()->set('seo.robots.force_default', true);

    $page = Page::create();

    $page->seo->update([
        'robots' => 'noindex,nofollow',
    ]);

    $page->refresh();

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('<meta name="robots" content="max-snippet:-1">', false);
});