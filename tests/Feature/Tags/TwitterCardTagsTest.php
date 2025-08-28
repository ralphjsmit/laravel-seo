<?php

use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Tags\TwitterCardTags;

it('can instantiate the `TwitterCardTags` class when no fallback image has been specified in config', function () {
    config()->set('seo.image.fallback', null);

    $SEOData = new SEOData(
        title: 'Unique Title',
        image: '/default/image.jpg',
    );

    $twitterCardTags = TwitterCardTags::initialize($SEOData);

    expect($twitterCardTags)
        ->toBeInstanceOf(TwitterCardTags::class);
});
