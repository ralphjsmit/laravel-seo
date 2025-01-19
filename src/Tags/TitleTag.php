<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Support\Tag;
use Illuminate\Support\Facades\Route;
class TitleTag extends Tag
{
    public string $tag = 'title';

    public function __construct(
        string $inner,
    ) {
        $this->inner = trim($inner);
        $currentMiddleware = collect(Route::gatherRouteMiddleware(Route::current()));
        $hasInertiaMiddleware = $currentMiddleware->contains(function ($middleware) {
            return is_subclass_of($middleware, "Inertia\Middleware");
        });

        if($hasInertiaMiddleware) {
            $this->attributes = ['inertia' => ""];
        }
    }

    public static function initialize(?SEOData $SEOData): ?Tag
    {
        $title = $SEOData?->title;

        if (! $title) {
            return null;
        }

        return new static(
            inner: $title,
        );
    }
}
