<?php

return [
    /**
     * Use this setting to provide a suffix that will be added after the title on each page.
     * If you don't want a suffix, you should specify an empty string.
     */
    'title' => [
        'suffix' => '',
    ],

    /**
     * Use this setting to specify a fallback description, which will be used on places
     * where we don't have a description set via an associated ->seo model or via
     * the ->getDynamicSEOData() method.
     */
    'fallback_description' => null,
];
