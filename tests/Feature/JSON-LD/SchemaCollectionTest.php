<?php

use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

it('can correctly render a custom JSON-LD Schemas markup', function () {
    $page = Page::create([]);

    $faqPageSchema = $this->faqTestSchema;

    $page::$overrides = [
        'schema' => SchemaCollection::make()->add($faqPageSchema),
    ];

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('"application/ld+json"', false)
        ->assertSee('<script type="application/ld+json">' . json_encode($faqPageSchema) . '</script>', false);
});

it('can correctly render a custom JSON-LD Schemas markup from a function', function () {
    $page = Page::create([]);

    $now = now();
    $yesterday = now()->yesterday();

    $page::$overrides = [
        'title' => 'Test title',
        'published_time' => $yesterday,
        'modified_time' => $now,
        'url' => 'https://example.com',
        'author' => 'Ralph J. Smit',
        'schema' => SchemaCollection::make()
            ->add(fn (SEOData $SEOData) => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $SEOData->url,
                ],
                'datePublished' => $SEOData->published_time->toIso8601String(),
                'dateModified' => $SEOData->modified_time->toIso8601String(),
                'headline' => $SEOData->title,
                'author' => [
                    [
                        '@type' => 'Person',
                        'name' => $SEOData->author,
                    ]
                ],
            ]),
    ];

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('"application/ld+json"', false)
        ->assertSee(
            '<script type="application/ld+json">' . json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => 'https://example.com',
                ],
                'datePublished' => $yesterday->toIso8601String(),
                'dateModified' => $now->toIso8601String(),
                'headline' => 'Test title',
                'author' => [
                    [
                        '@type' => 'Person',
                        'name' => 'Ralph J. Smit',
                    ]
                ],
            ]) . '</script>',
            false
        );
});

it('can correctly render the JSON-LD Schema markup: Article', function () {
    $created_at = now()->subDays(2);
    $updated_at = now();

    $page = Page::create([
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ]);

    $page::$overrides = [
        'title' => 'Test title',
        'image' => 'images/twitter-1743x1743.jpg',
        'author' => 'Ralph J. Smit',
        'schema' => SchemaCollection::make()->withArticle(function (SEOData $SEOData, Collection $article) {
            return $article->mergeRecursive([
                'alternativeHeadline' => 'My alternative headline',
                'author' => [
                    [
                        '@type' => 'Person',
                        'name' => 'Second author',
                    ]
                ]
            ]);
        }),
    ];

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('"application/ld+json"', false)
        ->assertSee(
            '<script type="application/ld+json">' .
                json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => route('seo.test-page', ['page' => $page]),
                    ],
                    'datePublished' => $created_at->toIso8601String(),
                    'dateModified' => $updated_at->toIso8601String(),
                    'headline' => 'Test title',
                    'image' => secure_url('images/twitter-1743x1743.jpg'),
                    'author' => [
                        [
                            '@type' => 'Person',
                            'name' => 'Ralph J. Smit',
                        ],
                        [
                            '@type' => 'Person',
                            'name' => 'Second author',
                        ],
                    ],
                    'alternativeHeadline' => 'My alternative headline',
                ]) . '</script>',
            false
        );
});


it('can correctly render the JSON-LD Schema markup: BreadcrumbList', function () {
    config()->set('seo.title.suffix', ' | Laravel SEO');

    $page = Page::create([]);

    $page::$overrides = [
        'title' => 'Test article',
        'enableTitleSuffix' => true,
        'url' => 'https://example.com/test/article',
        'schema' => SchemaCollection::make()->withBreadcrumbList(function (SEOData $SEOData, Collection $breadcrumb) {
            $items = $breadcrumb->get('itemListElement', []);

            $breadcrumb->put(
                'itemListElement',
                [
                    [
                        '@type' => 'ListItem',
                        'name' => 'Homepage',
                        'item' => 'https://example.com',
                    ],
                    [
                        '@type' => 'ListItem',
                        'name' => 'Category',
                        'item' => 'https://example.com/test',
                    ],
                    ...$items,
                    [
                        '@type' => 'ListItem',
                        'name' => 'Subarticle',
                        'item' => 'https://example.com/test/article/2',
                    ]
                ],
            );

            return $breadcrumb;
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
                            'name' => 'Homepage',
                            'item' => 'https://example.com',
                            'position' => 1,
                        ],
                        [
                            '@type' => 'ListItem',
                            'name' => 'Category',
                            'item' => 'https://example.com/test',
                            'position' => 2,
                        ],
                        [
                            '@type' => 'ListItem',
                            'name' => 'Test article | Laravel SEO',
                            'item' => 'https://example.com/test/article',
                            'position' => 3,
                        ],
                        [
                            '@type' => 'ListItem',
                            'name' => 'Subarticle',
                            'item' => 'https://example.com/test/article/2',
                            'position' => 4,
                        ],
                    ],
                ]) . '</script>',
            false
        );
});

it('can correctly render multiple custom JSON-LD Schemas markup', function () {
    $page = Page::create([]);

    $faqPageSchema = $this->faqTestSchema;

    $now = now();
    $yesterday = now()->yesterday();

    $page::$overrides = [
        'title' => 'Test title',
        'published_time' => $yesterday,
        'modified_time' => $now,
        'url' => 'https://example.com',
        'author' => 'Ralph J. Smit',
        'schema' => SchemaCollection::make()
            ->add($faqPageSchema)
            ->add(fn (SEOData $SEOData) => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $SEOData->url,
                ],
                'datePublished' => $SEOData->published_time->toIso8601String(),
                'dateModified' => $SEOData->modified_time->toIso8601String(),
                'headline' => $SEOData->title,
                'author' => [
                    [
                        '@type' => 'Person',
                        'name' => $SEOData->author,
                    ]
                ],
            ]),
    ];

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('"application/ld+json"', false)
        ->assertSee('<script type="application/ld+json">' . json_encode($faqPageSchema) . '</script>', false)
        ->assertSee(
            '<script type="application/ld+json">' . json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => 'https://example.com',
                ],
                'datePublished' => $yesterday->toIso8601String(),
                'dateModified' => $now->toIso8601String(),
                'headline' => 'Test title',
                'author' => [
                    [
                        '@type' => 'Person',
                        'name' => 'Ralph J. Smit',
                    ]
                ],
            ]) . '</script>',
            false
        );
});
