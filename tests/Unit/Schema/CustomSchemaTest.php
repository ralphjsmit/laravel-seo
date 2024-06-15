<?php

use RalphJSmit\Laravel\SEO\Schema\CustomSchema;

it('can construct a custom faq schema', function () {
    $schema = new CustomSchema([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
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
    ]);

    expect((string) $schema->render())
        ->toBe(
            '<script type="application/ld+json">' . json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => [
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
            ]) . '</script>'
        );
});
