<?php

return [
    'title' => [
        /**
         * Use this setting to let the package automatically infer a title from the url, if no other title
         * was given. This will be very useful on pages where you don't have an Eloquent model for, or where you
         * don't want to hardcode the title.
         *
         * For example, if you have a with the url '/foo/about-me', we'll automatically set the title to 'About me' and append the site suffix.
         */
        'infer_title_from_url' => true,

        /**
         * Use this setting to provide a suffix that will be added after the title on each page.
         * If you don't want a suffix, you should specify an empty string.
         */
        'suffix' => '',
    ],

    /**
     * Use this setting to specify a fallback description, which will be used on places
     * where we don't have a description set via an associated ->seo model or via
     * the ->getDynamicSEOData() method.
     */
    'fallback_description' => null,
];
