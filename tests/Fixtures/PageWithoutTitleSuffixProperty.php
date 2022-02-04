<?php

namespace RalphJSmit\Laravel\SEO\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use RalphJSmit\Laravel\SEO\Support\HasSEO;

class PageWithoutTitleSuffixProperty extends Model
{
    use HasSEO;

    public bool $enableTitleSuffix = false;

    protected $guarded = [];

    protected $table = 'pages';
}