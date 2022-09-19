<?php

use Illuminate\Database\Eloquent\Model;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\TagManager;

if ( ! function_exists('seo') ) {
    function seo(Model|SEOData $source = null): TagManager
    {
        $tagManager = app(TagManager::class);

        if ( $source ) {
            $tagManager->for($source);
        }

        return $tagManager;
    }
}