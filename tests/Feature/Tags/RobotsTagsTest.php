<?php

use function Pest\Laravel\get;

it('can display the Google robots tag', function () {
    get(route('seo.test-plain'))
        ->assertSee('<meta name="robots" content="max-snippet:-1,max-image-preview:large,max-video-preview:-1">', false);
});
