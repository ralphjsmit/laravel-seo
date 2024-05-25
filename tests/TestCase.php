<?php

namespace RalphJSmit\Laravel\SEO\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use RalphJSmit\Laravel\SEO\LaravelSEOServiceProvider;

class TestCase extends Orchestra
{
    public array $faqTestSchema = [
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
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'RalphJSmit\\Laravel\\SEO\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelSEOServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        Schema::enableForeignKeyConstraints();

        (include __DIR__ . '/../database/migrations/create_seo_table.php.stub')->up();
        (include __DIR__ . '/../tests/Fixtures/migrations/create_pages_table.php')->up();
    }
}
