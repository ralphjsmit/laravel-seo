<?php

use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Facades\SEOManager;
use RalphJSmit\Laravel\SEO\Support\MetaTag;
use RalphJSmit\Laravel\SEO\Support\SEOData;

use function Pest\Laravel\get;

it('can replace the title if we\'re on the homepage', function (?string $homepageTitleConfig, string $expectedTitle) {
    config()->set('seo.title.homepage_title', $homepageTitleConfig);
    config()->set('seo.title.suffix', '| My Website suffix');

    get(route('seo.test-home'))
        ->assertSee($expectedTitle);
})->with([
    [null, '| My Website suffix'],
    ['Custom homepage title', 'Custom homepage title'],
]);

it('can pipe the SEOData through the transformer before putting it into the collection', function () {
    config()->set('seo.title.infer_title_from_url', true);

    get(route('seo.test-plain'))
        ->assertSee('<title>Test Plain</title>', false);

    SEOManager::SEODataTransformer(function (SEOData $SEOData): SEOData {
        $SEOData->title = 'Transformed Title';

        return $SEOData;
    });

    SEOManager::SEODataTransformer(function (SEOData $SEOData): SEOData {
        $SEOData->description = 'Transformed description';

        return $SEOData;
    });

    get(route('seo.test-plain'))
        ->assertSee('<title>Transformed Title</title>', false)
        ->assertSee('Transformed description');
});

it('can pipe the generated tags through the transformers just before render', function () {
    SEOManager::tagTransformer(function (Collection $tags): Collection {
        return $tags->push(new MetaTag('test', 'content'));
    });

    get(route('seo.test-plain'))
        ->assertSee('<meta name="test" content="content">', false);
});