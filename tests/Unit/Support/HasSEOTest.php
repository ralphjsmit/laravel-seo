<?php

use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

it('automatically associates a SEO model on creation', function () {
    $page = Page::create();

    expect($page->seo)
        ->not->toBeNull();
});