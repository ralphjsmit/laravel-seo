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

it('escapes html tags in the rendered json to prevent breaking out of the script element', function () {
    $schema = new CustomSchema([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => 'Widget <!--<script>',
    ]);

    $rendered = (string) $schema->render();

    expect($rendered)
        ->not->toContain('<!--')
        ->not->toContain('<script>')
        ->toBe(
            '<script type="application/ld+json">' . json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => 'Widget <!--<script>',
            ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . '</script>'
        );

    expect(json_decode(
        str($rendered)->after('<script type="application/ld+json">')->before('</script>')->toString(),
        true
    ))->toBe([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => 'Widget <!--<script>',
    ]);
});
