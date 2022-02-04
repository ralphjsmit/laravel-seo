# Never worry about Laravel-SEO again!

I noticed that there weren't many SEO-packages for Laravel and that available ones are quite complex to set up and were very decoupled from the database. They only provided you with helpers to generate the tags, but you still had to use those helpers: nothing was generated automatically.

I wanted to provide a solution that allows generates valid and useful meta tags straight out-of-the-box, with limited initial configuration, whilst still providing a simple, but powerful API to work with.

If you're familiar with Spatie's media-library package, this package works in almost the same way, only then for SEO. I'm sure it will be very helpful for you, as it's usually best to SEO attention right from the beginning.

Here are a few examples of what you can do::

```php
$post = Post::find(1);

$post->addSEO();

$post->seo->update([
   'title' => 'My great post',
   'description' => 'This great post will enhance your live.',
]);
```

It will render the SEO tags directly on your page:

```blade
<!DOCTYPE html>
<html>
<head>
    {{ seo()->for($page) }}
    
    {{-- No need to separately render a <title> tag or any other meta tags! --}}
</head>
```

It even allows you to dynamically retrieve SEO data from your Model, without having to save it manually to the SEO model. The below code will require zero additional work from you or from your users:

```php
class Post extends Model
{
    use HasSEO;
    
    // One time setup, no further work needed! 
    protected static function booted()
    {
        static::created(function (self $model) {
            $model->addSEO();
        });
    }    

    // Override all the properties you want:
    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: $this->excerpt,
        );
    }
}
```





