<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use Illuminate\Support\Facades\Route;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Support\Tag;

class TitleTag extends Tag
{
    public string $tag = 'title';

    public function __construct(
        string $inner,
    ) {
        $this->inner = trim($inner);

        if ($this->isCurrentRouteInertiaRoute()) {
            $this->attributes['inertia'] = true;
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

    protected function isCurrentRouteInertiaRoute(): bool
    {
        $currentRoute = Route::current();

        if (! $currentRoute) {
            return false;
        }

        return collect(Route::gatherRouteMiddleware($currentRoute))->contains(function (string $middleware) {
            return is_subclass_of($middleware, \Inertia\Middleware::class);
        });
    }
}
