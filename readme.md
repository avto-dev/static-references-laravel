<p align="center">
  <img alt="laravel" src="https://habrastorage.org/webt/59/e1/c4/59e1c40b83e9d293787547.png" width="70" height="70" />
</p>

# Laravel-пакет для работы со статическими справочниками

![Packagist](https://img.shields.io/packagist/v/avto-dev/static-references-laravel.svg?style=flat&maxAge=30)
[![Build Status](https://scrutinizer-ci.com/g/avto-dev/static-references-laravel/badges/build.png?b=master)](https://scrutinizer-ci.com/g/avto-dev/static-references-laravel/build-status/master)
![StyleCI](https://styleci.io/repos/107638384/shield?style=flat&maxAge=30)
[![Code Coverage](https://scrutinizer-ci.com/g/avto-dev/static-references-laravel/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/avto-dev/static-references-laravel/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/avto-dev/static-references-laravel/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/avto-dev/static-references-laravel/?branch=master)
![GitHub issues](https://img.shields.io/github/issues/avto-dev/static-references-laravel.svg?style=flat&maxAge=30)

При помощи данного пакета вы сможете интегрировать сервис по работе со статическими справочниками в ваше **Laravel 5.x** приложение с помощью нескольких простых шагов.

## Установка

Для установки данного пакета выполните в терминале следующую команду:

```shell
$ composer require avto-dev/static-references-laravel "1.*"
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
     * `AutoCategoriesReference::class`;
     * `AutoCategories`;
     * `autoCategories`;
 * **Регионы субъектов** `AutoRegionsReference`
   * Бинд-алиасы: 
     * `AutoRegions`;
     * `autoRegions`;
 * **Регистрационные действия** `RegistrationActionsReference`
   * Бинд-алиасы:
     * `RegistrationActions`;
     * `registrationActions`;

### Примеры работы

Использование справочника "Категории транспортных средств":
```php
<?php

use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoriesReference;
use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoryEntry;
use AvtoDev\StaticReferencesLaravel\StaticReferences;

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

## Тестирование

Для тестирования данного пакета используется фреймворк `phpunit`. Для запуска тестов выполните в терминале:

```shell
$ git clone git@github.com:avto-dev/static-references-laravel.git
$ cd ./static-references-laravel
$ composer update --dev
$ composer test
```

## Поддержка и развитие

Если у вас возникли какие-либо проблемы по работе с данным пакетом, пожалуйста, создайте соответствующий `issue` в данном репозитории.

Если вы способны самостоятельно реализовать тот функционал, что вам необходим - создайте PR с соответствующими изменениями. Крайне желательно сопровождать PR соответствующими тестами, фиксирующими работу ваших изменений. После проверки и принятия изменений будет опубликована новая минорная версия.

## Лицензирование

Код данного пакета распространяется под лицензией **MIT**.

[getcomposer]:https://getcomposer.org/download/
