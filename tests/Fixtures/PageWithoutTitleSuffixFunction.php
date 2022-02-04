<?php

namespace RalphJSmit\Laravel\SEO\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use RalphJSmit\Laravel\SEO\Support\HasSEO;

class PageWithoutTitleSuffixFunction extends Model
{
    use HasSEO;

    public bool $enableTitleSuffix = true;

    protected $guarded = [];

    protected $table = 'pages';

    public function enableTitleSuffix(): bool
    {
        return false;
    }
}