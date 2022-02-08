<?php

namespace RalphJSmit\Laravel\SEO\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getSEODataTransformers()
 * @method static array getTagTransformers()
 * @method static \RalphJSmit\Laravel\SEO\SEOManager SEODataTransformer( Closure $transformer )
 * @method static \RalphJSmit\Laravel\SEO\SEOManager tagTransformer( Closure $transformer )
 */
class SEOManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \RalphJSmit\Laravel\SEO\SEOManager::class;
    }
}