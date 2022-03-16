# Changelog

All notable changes to `laravel-seo` will be documented in this file.

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
