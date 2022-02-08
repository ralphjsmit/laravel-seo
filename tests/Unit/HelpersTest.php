<?php

use RalphJSmit\Laravel\SEO\TagManager;

it('can get the TagManager', function () {
    expect(seo())->toBeInstanceOf(TagManager::class);
});