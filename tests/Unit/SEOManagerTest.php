<?php

use RalphJSmit\Laravel\SEO\SEOManager;

test('the SEOManager singleton works as expected', function () {
    $managerA = app(SEOManager::class);
    $managerB = app(SEOManager::class);
    $managerC = app(SEOManager::class);

    expect($managerA)->toBe($managerB)->toBe($managerC);
});