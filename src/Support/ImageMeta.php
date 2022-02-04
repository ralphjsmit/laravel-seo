<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Support\Str;

class ImageMeta
{
    public function __construct(string $imagePath)
    {
        $path = public_path(Str::of($imagePath)->after('public')->trim('/'));

        [$width, $height] = getimagesize($path);

        $this->width = $width;
        $this->height = $height;
    }
}