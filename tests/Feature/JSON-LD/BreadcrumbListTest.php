<?php

use RalphJSmit\Laravel\SEO\Schema\BreadcrumbListSchema;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

it('does not render by default the JSON-LD Schema markup: BreadcrumbList', function () {
    get(route('seo.test-plain'))
        ->assertDontSee('"application/ld+json"')
        ->assertDontSee('"@type": "BreadcrumbList"');
});

it('can correctly render the JSON-LD Schema markup: BreadcrumbList', function () {
    config()->set('seo.title.suffix', ' | Laravel SEO');

    $page = Page::create([
        'created_at' => now()->subDays(2),
    ]);

    $page::$overrides = [
        'title' => 'Test article',
        'enableTitleSuffix' => true,
        'url' => 'https://example.com/test/article',
        'schema' => SchemaCollection::initialize()->addBreadcrumbs(function (BreadcrumbListSchema $breadcrumbList): BreadcrumbListSchema {
            return $breadcrumbList->prependBreadcrumbs([
                'Homepage' => 'https://example.com',
                'Category' => 'https://example.com/test',
            ])->appendBreadcrumbs([
                'Subarticle' => 'https://example.com/test/article/2',
            ]);
        }),
    ];

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('"application/ld+json"', false)
        ->assertSee(
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
                            'name' => 'Test article | Laravel SEO',
                            'item' => 'https://example.com/test/article',
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 4,
                            'name' => 'Subarticle',
                            'item' => 'https://example.com/test/article/2',
                        ],
                    ],
                ]) . '</script>',
            false
        );
});
