<?php

use RalphJSmit\Laravel\SEO\Facades\SEOManager;

test('the SEOManager facade works as expected', function () {
    $managerA = SEOManager::getFacadeRoot();
    $managerB = SEOManager::getFacadeRoot();

    SEOManager::clearResolvedInstances();

    $managerC = SEOManager::getFacadeRoot();
    $managerD = SEOManager::getFacadeRoot();

    $managerE = app(\RalphJSmit\Laravel\SEO\SEOManager::class);

    expect($managerA)
        ->toBe($managerB)
        ->toBe($managerC)
        ->toBe($managerD)
        ->toBe($managerE);
});