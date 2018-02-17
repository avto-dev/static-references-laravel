<p align="center">
  <img src="https://laravel.com/assets/img/components/logo-laravel.svg" alt="Laravel" width="240" />
</p>

# Laravel-пакет для работы со статическими справочниками

[![Version][badge_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
![StyleCI][badge_styleci]
[![Code Coverage][badge_coverage]][link_build]
[![Code Quality][badge_quality]][link_build]
[![License][badge_license]][link_license]
[![Issues][badge_issues]][link_issues]
[![Downloads][badge_downloads]][link_packagist]

При помощи данного пакета вы сможете интегрировать сервис по работе со статическими справочниками в ваше **Laravel >=5.4** приложение с помощью нескольких простых шагов.

## Установка

Для установки данного пакета выполните в терминале следующую команду:

```shell
$ composer require avto-dev/static-references-laravel "^1.1.2"
```

> Для этого необходим установленный `composer`. Для его установки перейдите по [данной ссылке][getcomposer].

> Обратите внимание на то, что необходимо фиксировать мажорную версию устанавливаемого пакета.

Если вы используете Laravel версии 5.5 и выше, то сервис-провайдер данного пакета будет зарегистрирован автоматически. В противном случае вам необходимо самостоятельно зарегистрировать сервис-провайдер в секции `providers` файла `./config/app.php`:

```php
'providers' => [
    // ...
    AvtoDev\StaticReferencesLaravel\StaticReferencesServiceProvider::class,
]
```

> После чего вы можете *(но в большинстве случаев в этом нет необходимости)* "опубликовать" файл конфигурации с помощью команды:
> 
> ```shell
> $ ./artisan vendor:publish --provider="AvtoDev\StaticReferencesLaravel\StaticReferencesServiceProvider"
> ```
> 
> Данная команда создаст файл `./config/static-references.php` с настройками "по умолчанию" которые, при необходимости, вы можете переопределить на свои.
> 
> С новыми версиями пакета могут добавляться новые опции в конфигурационном файле. Пожалуйста, не забывайте время от времени проверять этот момент.

## Использование

Данный пакет регистрирует IoC сервис-контейнер объекта `StaticReferences`, который является единой точкой входа в справочники.

Доступ к нему осуществляется как с помощью непосредственного извлечения по имени класса `StaticReferences::class` или алиасу, так и с помощью фасада `StaticReferencesFacade`.

### Семантика и принцип работы

Основные понятия:

 * **Справочник** - это объект, который реализует методы для чтения файлов-данных справочника из файла и доступа к объектам-элементам справочника. Наследуется от класса `Illuminate\Support\Collection`.
 * **Провайдер справочника** (`ReferenceProvider`) - это объект, реализующий интерфейс `ReferenceProviderInterface`, который отвечает за регистрацию справочника.
 * **Бинд-алиас** - это строковое значение, по которому происходит извлечение инстанса справочника.
 * **Регистрация справочника** - это процесс связывания биндов-алиасов с инстансом конкретного справочника с помощью соответствующих методов провайдера справочника.
 * **Отложенное создание справочника** - это функция провайдера справочника, которая указывает не вызывать метод `instance()` провайдера справочника при его *(провайдера справочника)* создании.

Так как процесс чтения данных из файлов-данных, их нормализация и приведение к объектам - довольно "дорогая" операция - реализован паттерн отложенного (`deferred`) создания инстансов справочников *(которые занимаются инициализацией своих данных в конструкторе)*, и их конструкторы вызываются только при непосредственном обращении к ним с помощью биндов-алиасов, что были зарегистрированы в провайдере справочника.

Более того - реализовано кэширование объектов-справочников, что позволяет читать файлы-данные только один раз, а последующая инициализация инстанса справочника происходит уже со всеми данными почти мгновенно.

Используемое хранилище кэша используется то, что установлено по умолчанию для вашего Laravel-приложения, но при необходимости - используемое хранилище, время жизни кэша, и использование кэша в целом вы можете переопределить, опубликовав файл-конфигурацию, и указав в нем необходимые значения.

Так же вы можете расширить данный пакет своими справочниками - для этого необходимо в файле-конфигурации перечислить классы провайдеров ваших справочников, и наполнить их необходимым функционалом.

> Данный пакет является пригодным к использованию в высоко-нагруженных приложениях.

### Реализованные справочники

На данный момент реализованы следующие справочники:

 * **Категории транспортных средств**: `AutoCategoriesReference`
   * Бинд-алиасы:
     * `AutoCategoriesReference::class`
     * `AutoCategories`
     * `autoCategories`
 * **Регионы субъектов** `AutoRegionsReference`
   * Бинд-алиасы: 
     * `AutoRegionsReference::class`
     * `AutoRegions`
     * `autoRegions`
 * **Регистрационные действия** `RegistrationActionsReference`
   * Бинд-алиасы:
     * `RegistrationActionsReference::class`
     * `RegistrationActions`
     * `registrationActions`

### Примеры использования

<details>
  <summary>Справочник "Категории транспортных средств"</summary>
  
```php
<?php

use AvtoDev\StaticReferencesLaravel\StaticReferences;
use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoryEntry;
use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoriesReference;

// Извлекаем инстанс статических справочников из IoC Laravel
/** @var AutoCategoriesReference $auto_categories */
$auto_categories = app(StaticReferences::class)->make(AutoCategoriesReference::class);

// Перебираем все категории ТС
$auto_categories->each(function (AutoCategoryEntry $category) {
    $category->getCode();
    $category->getDescription();
});

// Получаем коды всех категорий одним массивом
$codes = $auto_categories->pluck('code')->toArray(); // === ['A', 'B1', 'B', ...];

// Получаем массив вида '%код_категории% => %её_описание%'
$map = $auto_categories->mapWithKeys(function (AutoCategoryEntry $category) {
    return [$category->getCode() => $category->getDescription()];
})->all();

// Проверяем наличие категории по коду
$auto_categories->hasCode('B1'); // true
$auto_categories->hasCode('A9'); // false

// Получаем объект категории по его коду
$category_b1 = $auto_categories->getByCode('B1');
/*
AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoryEntry {
  #code: "B1"
  #description: "Трициклы"
}
*/
```
</details>

<details>
  <summary>Справочник "Регионы субъектов"</summary>
  
```php
<?php

use AvtoDev\StaticReferencesLaravel\StaticReferences;
use AvtoDev\StaticReferencesLaravel\References\AutoRegions\AutoRegionEntry;
use AvtoDev\StaticReferencesLaravel\References\AutoRegions\AutoRegionsReference;

/** @var AutoRegionsReference $auto_regions */
$auto_regions = app(StaticReferences::class)->make(AutoRegionsReference::class);

// Перебираем все регионы субъектов
$auto_regions->each(function (AutoRegionEntry $region) {
    $region->getRegionCode(); // код субъекта РФ
    $region->getAutoCodes(); // автомобильные коды (коды ГИБДД)
    $region->getIso31662(); // код региона по стандарту ISO-31662
    $region->getOkato(); // код региона по ОКАТО
    $region->getShortTitles(); // варианты короткого наименования региона
    $region->getTitle(); // заголовок региона
    $region->getType(); // тип (республика/край/и т.д.)
});

// Получаем заголовки всех регионов одним массивом
$titles = $auto_regions->pluck('title')->toArray(); // === ['Республика Адыгея', 'Республика Алтай', ...];

// Получаем массив вида '%название_региона% => [%его_гибдд_коды%]'
$map = $auto_regions->mapWithKeys(function (AutoRegionEntry $region) {
    return [$region->getTitle() => $region->getAutoCodes()];
})->all();

// Получаем объект региона по его заголовку
$moscow_region = $auto_regions->getByTitle('Москва');
/*
AvtoDev\StaticReferencesLaravel\References\AutoRegions\AutoRegionEntry {
  #title: "Москва"
  #short_titles: array:2 [
    0 => "Москва"
    1 => "МСК"
  ]
  #region_code: 77
  #auto_codes: array:8 [
    0 => 77
    1 => 97
    2 => 99
    3 => 177
    4 => 197
    5 => 199
    6 => 799
    7 => 777
  ]
  #okato: "45"
  #iso_31662: "RU-MOW"
  #type: "Город федерального значения"
}
*/

$auto_regions->hasAutoCode(177); // true
$auto_regions->hasAutoCode(666); // false
```
</details>

<details>
  <summary>Справочник "Регистрационные действия"</summary>
  
```php
<?php

use AvtoDev\StaticReferencesLaravel\StaticReferences;
use AvtoDev\StaticReferencesLaravel\References\RegistrationActions\RegistrationActionEntry;
use AvtoDev\StaticReferencesLaravel\References\RegistrationActions\RegistrationActionsReference;

/** @var RegistrationActionsReference $reg_actions */
$reg_actions = app(StaticReferences::class)->make(RegistrationActionsReference::class);

// Перебираем все регистрационные действия
$reg_actions->each(function (RegistrationActionEntry $reg_action) {
    $reg_action->getCodes(); // коды регистрационного действия
    $reg_action->getDescription(); // описание регистрационного действия
});

// Получаем описания всех регистрационных действий одним массивом
$descriptions = $reg_actions->pluck('description')->toArray(); // === ['Первичная регистрация', ...];

// Получаем массив вида '%описание_рег_действия% => [%его_коды%]'
$map = $reg_actions->mapWithKeys(function (RegistrationActionEntry $reg_action) {
    return [$reg_action->getDescription() => $reg_action->getCodes()];
})->all();

// Получаем объект категории по его заголовку
$reg_action = $reg_actions->getByCode(11); // Первичная регистрация
/*
AvtoDev\StaticReferencesLaravel\References\RegistrationActions\RegistrationActionEntry {
  #codes: array:1 [
    0 => 11
  ]
  #description: "Первичная регистрация"
}
*/

$reg_actions->hasCode(11); // true
$reg_actions->hasCode(666); // false
```
</details>

## Тестирование

Для тестирования данного пакета используется фреймворк `phpunit`. Для запуска тестов выполните в терминале:

```shell
$ git clone git@github.com:avto-dev/static-references-laravel.git ./static-references-laravel && cd $_
$ composer update --dev
$ composer test
```

## Поддержка и развитие

Если у вас возникли какие-либо проблемы по работе с данным пакетом, пожалуйста, создайте соответствующий `issue` в данном репозитории.

Если вы способны самостоятельно реализовать тот функционал, что вам необходим - создайте PR с соответствующими изменениями. Крайне желательно сопровождать PR соответствующими тестами, фиксирующими работу ваших изменений. После проверки и принятия изменений будет опубликована новая минорная версия.

## Лицензирование

Код данного пакета распространяется под лицензией **MIT**.

[badge_version]:https://img.shields.io/packagist/v/avto-dev/static-references-laravel.svg?style=flat&maxAge=30
[badge_license]:https://img.shields.io/packagist/l/avto-dev/static-references-laravel.svg?style=flat&maxAge=30
[badge_build_status]:https://scrutinizer-ci.com/g/avto-dev/static-references-laravel/badges/build.png?b=master
[badge_styleci]:https://styleci.io/repos/107638384/shield?style=flat
[badge_coverage]:https://scrutinizer-ci.com/g/avto-dev/static-references-laravel/badges/coverage.png?b=master
[badge_quality]:https://scrutinizer-ci.com/g/avto-dev/static-references-laravel/badges/quality-score.png?b=master
[badge_issues]:https://img.shields.io/github/issues/avto-dev/static-references-laravel.svg?style=flat&maxAge=30
[badge_downloads]:https://img.shields.io/packagist/dt/avto-dev/static-references-laravel.svg?style=flat&maxAge=30
[link_packagist]:https://packagist.org/packages/avto-dev/static-references-laravel
[link_license]:https://github.com/avto-dev/static-references-laravel/blob/master/LICENSE
[link_build]:https://scrutinizer-ci.com/g/avto-dev/static-references-laravel
[link_build_status]:https://scrutinizer-ci.com/g/avto-dev/static-references-laravel/build-status/master
[link_issues]:https://github.com/avto-dev/static-references-laravel/issues
[getcomposer]:https://getcomposer.org/download/
