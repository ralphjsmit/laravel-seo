<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Exception;

class ImageMeta
{
    public ?int $width = null;

    public ?int $height = null;

    public function __construct(string $path)
    {
        $publicPath = public_path($path);

        if ( ! is_file($publicPath) ) {
            report(new Exception("Path {$publicPath} is not a file."));

            return;
        }

        [$width, $height] = getimagesize($publicPath);

        $this->width = $width;
        $this->height = $height;
    }
}
