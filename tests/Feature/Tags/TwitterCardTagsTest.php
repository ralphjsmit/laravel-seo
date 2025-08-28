<?php

use RalphJSmit\Laravel\SEO\Support\SEOData;

test('Can init the TwitterCardTags class', function () {
    $SEOData = new SEOData(
        title: 'Unique Title',
        image: '/default/image.jpg',
    );

    $collection = \RalphJSmit\Laravel\SEO\Tags\TwitterCardTags::initialize($SEOData);

    expect($collection)->toBeInstanceOf(\RalphJSmit\Laravel\SEO\Tags\TwitterCardTags::class);
});
