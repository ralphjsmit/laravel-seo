<?php

use RalphJSmit\Laravel\SEO\TagManager;

if ( ! function_exists('seo') ) {
    function seo(): TagManager
    {
        return app(TagManager::class);
    }
}