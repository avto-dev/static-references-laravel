<p align="center">
  <img src="https://laravel.com/assets/img/components/logo-laravel.svg" alt="Laravel" width="240" />
</p>

# Laravel-пакет для работы со статическими справочниками

[![Version][badge_packagist_version]][link_packagist]
[![Version][badge_php_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
[![Coverage][badge_coverage]][link_coverage]
[![Downloads count][badge_downloads_count]][link_packagist]
[![License][badge_license]][link_license]

При помощи данного пакета вы сможете интегрировать сервис по работе со статическими справочниками в ваше **Laravel >=5.4** приложение с помощью нескольких простых шагов.

## Install

Require this package with composer using the following command:

```shell
$ composer require avto-dev/static-references-laravel "^3.0"
```

> Installed `composer` is required ([how to install composer][getcomposer]).

> You need to fix the major version of package.

Если вы используете Laravel версии 5.5 и выше, то сервис-провайдер данного пакета будет зарегистрирован автоматически. В противном случае вам необходимо самостоятельно зарегистрировать сервис-провайдер в секции `providers` файла `./config/app.php`:

```php
'providers' => [
    // ...
    AvtoDev\StaticReferences\ServiceProvider::class,
]
```

## Использование

Данный пакет регистрирует IoC-контейнеры объектов справочников, и это является является единой точкой входа в сами справочники. Использование именно IoC (вместо `new SomeReference`) обусловлено тем, что сервис провайдер данного пакета обеспечивает кэширование объектов справочников (целиком) и проверку на валидность кэша (в версиях **до 2.0** была необходимость "сбрасывать" весь кэш ручками при обновлении пакета).

Если вы предпочитаете использовать фасады для доступа к справочникам - они так же имеют место быть.

### Реализованные справочники

На данный момент реализованы следующие справочники:

 * `AvtoDev\StaticReferences\References\AutoCategories\AutoCategories`
 * `AvtoDev\StaticReferences\References\AutoRegions\AutoRegions`
 * `AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActions`
 * `AvtoDev\StaticReferences\References\RepairMethods\RepairMethods`
 * `AvtoDev\StaticReferences\References\AutoFines\AutoFines`

Класс справочника | Описание | Поля данных
----------------- | -------- | -----------
`AutoCategories` | Категории транспортных средств | `code` - Код категории <br /> `description` - Описание категории
`AutoRegions` | Регионы субъектов | `title` Заголовок региона <br /> `short_titles` - Варианты короткого наименования региона <br /> `region_code` - Код субъекта РФ <br /> `auto_codes` - Автомобильные коды (коды ГИБДД) <br /> `okato` - Код региона по ОКАТО <br /> `iso_31662` - Код региона по стандарту ISO-31662 <br /> `type` - Тип (республика/край/и т.д.)
`RegistrationActions` | Регистрационные действия | `codes` - Коды регистрационного действия <br /> `description` - Описание регистрационного действия
`RepairMethods` | Методы ремонта | `codes` - Коды метода ремонта <br /> `description` - Описание метода ремонта
`AutoFines` | Правонарушения в области дорожного движения | `article` - Статья правонарушения <br /> `description` - Описание правонарушения

### Примеры использования

Справочник "Категории транспортных средств":

```php
<?php

use AvtoDev\StaticReferences\References\AutoCategories\AutoCategoryEntry;
use AvtoDev\StaticReferences\References\AutoCategories\AutoCategories;

// Извлекаем инстанс статических справочников из IoC Laravel
/** @var AutoCategories $auto_categories */
$auto_categories = resolve(AutoCategories::class);

// Перебираем все категории ТС
$auto_categories->each(function (AutoCategoryEntry $category) {
    $category->getCode();        // код категории
    $category->getDescription(); // её описание
});

// Проверяем наличие категории по коду
$auto_categories->hasCode('B1'); // true
$auto_categories->hasCode('A9'); // false

// Получаем объект категории по его коду
$category_b1 = $auto_categories->getByCode('B1');
```

Справочник "Регионы субъектов":
  
```php
<?php

use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;

/** @var AutoRegions $auto_regions */
$auto_regions = resolve(AutoRegions::class);

// Перебираем все регионы субъектов
$auto_regions->each(function (AutoRegionEntry $region) {
    $region->getRegionCode();  // код субъекта РФ
    $region->getAutoCodes();   // автомобильные коды (коды ГИБДД)
    $region->getIso31662();    // код региона по стандарту ISO-31662
    $region->getOkato();       // код региона по ОКАТО
    $region->getShortTitles(); // варианты короткого наименования региона
    $region->getTitle();       // заголовок региона
    $region->getType();        // тип (республика/край/и т.д.)
});

// Получаем заголовки всех регионов одним массивом
$titles = collect($auto_regions->toArray())->pluck('title')->toArray(); // ['Республика Адыгея', 'Республика Алтай', ...];

// Получаем объект региона по его заголовку
$moscow_region = $auto_regions->getByTitle('Москва');

// Проверяем наличие региона по его коду
$auto_regions->hasAutoCode(177); // true
$auto_regions->hasAutoCode(666); // false
```

### Testing

For package testing we use `phpunit` framework and `docker-ce` + `docker-compose` as develop environment. So, just write into your terminal after repository cloning:

```bash
$ make build
$ make latest # or 'make lowest'
$ make test
```

## Changes log

[![Release date][badge_release_date]][link_releases]
[![Commits since latest release][badge_commits_since_release]][link_commits]

Changes log can be [found here][link_changes_log].

## Support

[![Issues][badge_issues]][link_issues]
[![Issues][badge_pulls]][link_pulls]

If you will find any package errors, please, [make an issue][link_create_issue] in current repository.

## License

This is open-sourced software licensed under the [MIT License][link_license].

[badge_packagist_version]:https://img.shields.io/packagist/v/avto-dev/static-references-laravel.svg?maxAge=180
[badge_php_version]:https://img.shields.io/packagist/php-v/avto-dev/static-references-laravel.svg?longCache=true
[badge_build_status]:https://travis-ci.org/avto-dev/static-references-laravel.svg?branch=master
[badge_coverage]:https://img.shields.io/codecov/c/github/avto-dev/static-references-laravel/master.svg?maxAge=60
[badge_downloads_count]:https://img.shields.io/packagist/dt/avto-dev/static-references-laravel.svg?maxAge=180
[badge_license]:https://img.shields.io/packagist/l/avto-dev/static-references-laravel.svg?longCache=true
[badge_release_date]:https://img.shields.io/github/release-date/avto-dev/static-references-laravel.svg?style=flat-square&maxAge=180
[badge_commits_since_release]:https://img.shields.io/github/commits-since/avto-dev/static-references-laravel/latest.svg?style=flat-square&maxAge=180
[badge_issues]:https://img.shields.io/github/issues/avto-dev/static-references-laravel.svg?style=flat-square&maxAge=180
[badge_pulls]:https://img.shields.io/github/issues-pr/avto-dev/static-references-laravel.svg?style=flat-square&maxAge=180
[link_releases]:https://github.com/avto-dev/static-references-laravel/releases
[link_packagist]:https://packagist.org/packages/avto-dev/static-references-laravel
[link_build_status]:https://travis-ci.org/avto-dev/static-references-laravel
[link_coverage]:https://codecov.io/gh/avto-dev/static-references-laravel/
[link_changes_log]:https://github.com/avto-dev/static-references-laravel/blob/master/CHANGELOG.md
[link_issues]:https://github.com/avto-dev/static-references-laravel/issues
[link_create_issue]:https://github.com/avto-dev/static-references-laravel/issues/new/choose
[link_commits]:https://github.com/avto-dev/static-references-laravel/commits
[link_pulls]:https://github.com/avto-dev/static-references-laravel/pulls
[link_license]:https://github.com/avto-dev/static-references-laravel/blob/master/LICENSE
[getcomposer]:https://getcomposer.org/download/
