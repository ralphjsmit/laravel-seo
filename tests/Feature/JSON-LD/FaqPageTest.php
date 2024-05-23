<?php

use RalphJSmit\Laravel\SEO\Schema\FaqPageSchema;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Tests\Fixtures\Page;

use function Pest\Laravel\get;

it('does not render by default the JSON-LD Schema markup: FaqPageTest', function () {
    get(route('seo.test-plain'))
        ->assertDontSee('"application/ld+json"')
        ->assertDontSee('"@type": "FAQPage"');
});

it('can correctly render the JSON-LD Schema markup: FaqPageTest', function () {
    config()->set('seo.title.suffix', ' | Laravel SEO');

    $page = Page::create([]);

    $page::$overrides = [
        'title' => 'Test FAQ',
        'enableTitleSuffix' => true,
        'url' => 'https://example.com/test/faq',
        'schema' => SchemaCollection::initialize()->addFaqPage(function (FaqPageSchema $faqPage): FaqPageSchema {
            return $faqPage
                ->addQuestion(name: 'Can this package add FaqPage to the schema?', acceptedAnswer: 'Yes!')
                ->addQuestion(name: 'Does it support multiple questions?', acceptedAnswer: 'Of course.');
        }),
    ];

    get(route('seo.test-page', ['page' => $page]))
        ->assertSee('"application/ld+json"', false)
        ->assertSee(
            '<script type="application/ld+json">' .
                json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => [
                        [
                            '@type' => 'Question',
                            'name' => 'Can this package add FaqPage to the schema?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Yes!',
                            ],
                        ],
                        [
                            '@type' => 'Question',
                            'name' => 'Does it support multiple questions?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Of course.',
                            ],
                        ],
                    ],
                ]) . '</script>',
            false
        );
});
