<?php

use RalphJSmit\Laravel\SEO\Schema\BreadcrumbListSchema;
use RalphJSmit\Laravel\SEO\Support\SEOData;

beforeEach(function () {
    $this->SEOData = new SEOData(
        title: 'Test article',
        url  : 'https://example.com/test/article',
    );
});

it('can construct Schema Markup: BreadcrumbList', function () {
    $articleSchema = new BreadcrumbListSchema($this->SEOData, [
        fn (BreadcrumbListSchema $breadcrumbList): BreadcrumbListSchema => $breadcrumbList->prependBreadcrumbs([
            'Homepage' => 'https://example.com',
            'Category' => 'https://example.com/test',
        ]),
        fn (BreadcrumbListSchema $breadcrumbList): BreadcrumbListSchema => $breadcrumbList->appendBreadcrumbs([
            'Subarticle' => 'https://example.com/test/article/2',
        ]),
    ]);

    expect((string) $articleSchema->render())->toBe(
        '<script type="application/ld+json">' .
        json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Homepage',
                    'item' => 'https://example.com',
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Category',
                    'item' => 'https://example.com/test',
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => 'Test article',
                    'item' => 'https://example.com/test/article',
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 4,
                    'name' => 'Subarticle',
                    'item' => 'https://example.com/test/article/2',
                ],
            ],
        ]) . '</script>'
    );
});
