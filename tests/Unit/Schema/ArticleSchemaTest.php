<?php

use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Schema\ArticleSchema;
use RalphJSmit\Laravel\SEO\Support\SEOData;

beforeEach(function () {
    $this->SEOData = new SEOData(
        title         : 'Test',
        description   : 'Description',
        author        : 'Ralph J. Smit',
        image         : 'https://example.com/image.jpg',
        url           : 'https://example.com/test',
        published_time: now()->subDays(3),
        modified_time : now(),
        articleBody   : '<p>Test</p>',
    );
});

it('can construct Schema Markup: Article', function () {
    $articleSchema = new ArticleSchema($this->SEOData, []);

    expect((string) $articleSchema->render())
        ->toBe(
            '<script type="application/ld+json">' .
            $string = json_encode([
                    '@context' => 'http://schema.org',
                    '@type' => 'Article',
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => 'https://example.com/test',
                    ],
                    'datePublished' => now()->subDays(3)->toIso8601String(),
                    'dateUpdated' => now()->toIso8601String(),
                    'headline' => 'Test',
                    'author' => [
                        '@type' => 'Person',
                        'name' => 'Ralph J. Smit',
                    ],
                    'description' => 'Description',
                    'image' => 'https://example.com/image.jpg',
                    'articleBody' => '<p>Test</p>',
                ]) . '</script>'
        );
});

it('can add multiple authors to Schema Markup: Article', function () {
    $articleSchema = new ArticleSchema($this->SEOData, [
        fn (ArticleSchema $article): ArticleSchema => $article
            ->addAuthor('Second author')
            ->markup(function (Collection $markup): Collection {
                return $markup->put('alternativeHeadline', 'My alternative headline');
            }),
    ]);

    expect((string) $articleSchema->render())->toBe(
        '<script type="application/ld+json">' .
        json_encode([
            '@context' => 'http://schema.org',
            '@type' => 'Article',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => 'https://example.com/test',
            ],
            'datePublished' => now()->subDays(3)->toIso8601String(),
            'dateUpdated' => now()->toIso8601String(),
            'headline' => 'Test',
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
            'description' => 'Description',
            'image' => 'https://example.com/image.jpg',
            'alternativeHeadline' => 'My alternative headline',
        ]) . '</script>'
    );
});