# Changelog

All notable changes to `laravel-seo` will be documented in this file.

## 1.0.4 - 2022-06-01

– Fix: remove accidental `dump()`.

## 1.0.3 - 2022-06-01

– Feat: support use of models without the related SEO-model in the database (#5).

## 1.0.2 - 2022-06-01

– Fix: OpenGraph specification
– Fix: using the ->imageMeta with a custom override URL.

## 1.0.1 - 2022-05-24

– Fix incorrect import #9.

## 0.7.0 - 2022-04-15

- Dynamic SEO model.

## 0.6.1 - 2022-04-06

- Fallback for models without ->seo.

## 0.6.0 - 2022-03-16

- Add support for sitemap tags.

## 0.5.3 - 2022-03-09

- Add support for canonical URLs.
- Refactor `$SEOData->url` resolution if we should get it from the current url.

## 0.5.2 - 2022-03-09

- Add support for image sizes on Twitter cards

## 0.5.1 - 2022-03-09

- Fix case where image size wasn't retrieved when it could be retrieved

## 0.5.0 - 2022-03-09

- Update implementation for handling of image paths: we now only accept public paths.

## 0.4.0 - 2022-03-04

- Add support for automatic `og:locale`

## 0.3.1 - 2022-02-17

- Use `https` for `@context` reference to schema.org.

## 0.3.0 - 2022-02-10

- Feat: separate title for the homepage.

## 0.2.0 - 2022-02-08

- Add articleBody

## 0.1.3 - 2022-02-08

- Fix migration name

## 0.1.2 - 2022-02-08

- Fix service provider name

## 0.1.1 - 2022-02-08

- Fix service provider namespace
