<?php

namespace RalphJSmit\Laravel\SEO\Support;

use const FILTER_VALIDATE_URL;

class Url
{
    /**
     * Determine whether the value is an absolute URL. `FILTER_VALIDATE_URL` only accepts ASCII, so
     * spaces and non-ASCII bytes (e.g. "屏幕截图.png" or the U+202F in macOS screenshot names)
     * are percent-encoded first. The value itself is never modified.
     */
    public static function isAbsolute(string $value): bool
    {
        $encoded = preg_replace_callback('/[ \x80-\xFF]/', fn (array $matches): string => rawurlencode($matches[0]), $value);

        return filter_var($encoded, FILTER_VALIDATE_URL) !== false;
    }
}
