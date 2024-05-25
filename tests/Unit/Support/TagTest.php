<?php

use RalphJSmit\Laravel\SEO\Support\Tag;

it('orders tag attributes', function () {
    $tag = new class extends Tag
    {
        public string $tag = 'link';
    };

    $tag->attributes = [
        'hreflang' => 'hreflang',
        'description' => 'description',
        'title' => 'title',
        'content' => 'content',
        'name' => 'name',
        'href' => 'href',
        'foo' => 'foo',
        'property' => 'property',
        'bar' => 'bar',
        'rel' => 'rel',
    ];

    expect((string) $tag->render())
        ->toBe('<link rel="rel" hreflang="hreflang" title="title" name="name" href="href" property="property" description="description" content="content" foo="foo" bar="bar">');
});
