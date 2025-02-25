<?php

use RalphJSmit\Laravel\SEO\Schema\ArticleSchema;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

beforeEach(function () {
    if (! file_exists($dir = public_path('images'))) {
        mkdir($dir, 0777, true);
    }

    copy(__DIR__ . '/../../Fixtures/images/twitter-72x72.jpg', public_path('images/twitter-72x72.jpg'));
    copy(__DIR__ . '/../../Fixtures/images/twitter-1743x1743.jpg', public_path('images/twitter-1743x1743.jpg'));
    copy(__DIR__ . '/../../Fixtures/images/twitter-4721x4721.jpg', public_path('images/twitter-4721x4721.jpg'));
    copy(__DIR__ . '/../../Fixtures/images/twitter-3597x1799.jpg', public_path('images/twitter-3597x1799.jpg'));
});

it('does not render by default the JSON-LD Schema markup: Article', function () {
get(route('seo.test-plain'))
->assertDontSee('"application/ld+json"')
->assertDontSee('"@type": "Article"');
    });

it('can correctly render the JSON-LD Schema markup: Article', function () {
    $page = Page::create([
        'created_at' => now()->subDays(2),
    ]);

    $page::$overrides = [
        'title' => 'Test title',
        'schema' => SchemaCollection::initialize()->addArticle(fn (ArticleSchema $article): ArticleSchema => $article->addAuthor('Second author')),
        'image' => 'images/twitter-1743x1743.jpg',
        'author' => 'Ralph J. Smit',
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
                    'datePublished' => now()->subDays(2)->toIso8601String(),
                    'dateModified' => now()->toIso8601String(),
                    'headline' => 'Test title',
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
                    'image' => secure_url('images/twitter-1743x1743.jpg'),
                ]) . '</script>',
            false
        );
});
