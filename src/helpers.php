<?php

use Illuminate\Database\Eloquent\Model;
use RalphJSmit\Laravel\SEO\TagManager;

if ( ! function_exists('seo') ) {
    function seo(Model $model = null): TagManager
    {
        $tagManager = app(TagManager::class);

        if ( $model ) {
            $tagManager->for($model);
        }

        return $tagManager;
    }
}