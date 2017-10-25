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

При помощи данного пакета вы сможете интегрировать сервис по работе со статическими справочниками в ваше **Laravel 5.x** приложение с 
помощью нескольких простых шагов.

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

Доступ к нему так же осуществляется как с помощью непосредственного извлечения по имени класса или алиасу, так и с помощью фасада `StaticReferencesFacade`.

### Семантика и принцип работы

Основные понятия:

 * **Справочник** - это объект, который реализует методы для чтения файлов-данных справочника из файла и доступа к объектам-элементам справочника. Наследуется от класса `Collection` от `Illuminate`.
 * **Провайдер справочника** (`ReferenceProvider`) - это объект, реализующий интерфейс `ReferenceProviderInterface`, который отвечает за регистрацию справочника.
 * **Бинд-алиас** - это строковое значение, по которому происходит извлечение инстанса справочника.
 * **Регистрация справочника** - это процесс связывания биндов-алиасов с инстансом конкретного справочника с помощью соответствующих методов провайдера справочника.
 * **Отложенное создание справочника** - это функция провайдера справочника, которая указывает не вызывать метод `instance()` провайдера справочника при его *(провайдера справочника)* создании.

Так как процесс чтения данных из файлов-данных, их нормализация и приведение к объектам - довольно "дорогая" операция - реализован паттерн отложенного (`deferred`) создания инстансов справочников *(которые занимаются инициализацией своих данных в конструкторе)*, и их конструкторы вызываются только при непосредственном обращении к ним с помощью биндов-алиасов, что были зарегистрированы в провайдере справочника.

Более того - реализовано кэширование объектов-справочников, что позволяет читать файлы-данные только один раз, а последующая инициализация инстанса справочника происходит уже со всеми данными почти мгновенно.

Используемое хранилище кэша по умолчанию используется то, что установлено по умолчанию для вашего Laravel-приложения, но при необходимости - используемое хранилище, время жизни кэша, и использование кэша в целом вы можете переопределить, опубликовав файл-конфигурацию, и указав в нем необходимые значения.

Так же вы можете расширить данный пакет своими справочниками - для этого необходимо в файле-конфигурации перечислить классы провайдеров ваших справочников, и наполнить их необходимым функционалом.

Данный пакет является пригодным к использования в высоко-нагруженных приложениях.

### Реализованные справочники

На данный момент реализованы следующие справочники:

 * **Категории транспортных средств** (`AutoCategoriesReference`), бинд-алиасы: `['AutoCategories', 'autoCategories']`;
 * **Регионы субъектов** (`AutoRegionsReference`), бинд-алиасы: `['AutoRegions', 'autoRegions']`;
 * **Регистрационные действия** (`RegistrationActionsReference`), бинд-алиасы: `['RegistrationActions', 'registrationActions']`;

### Примеры работы

Получение данных по коду субъекта РФ:
```php
use AvtoDev\StaticReferencesLaravel\StaticReferences;
use AvtoDev\StaticReferencesLaravel\References\AutoRegions\AutoRegionsReference;

// Извлекаем инстанс статических справочников из IoC Laravel
/** @var StaticReferences $references */
$references = app(StaticReferences::class);

// Извлекаем справочник по работе с регионами
/** @var AutoRegionsReference $regions */
$regions = $references->make('AutoRegions');
// или: $regions = $references->autoRegions;

// Получаем данные по региону с кодом '12'
// Все доступные методы смотрите в type-hint
dump($regions->getByRegionCode(12));

// Вернется следующий объект:
/* AvtoDev\StaticReferencesLaravel\References\AutoRegions\AutoRegionEntry {
  #title: "Республика Марий Эл"
  #short_titles: array:1 [
    0 => "Марий Эл"
  ]
  #region_code: 12
  #auto_code: array:1 [
    0 => 12
  ]
  #okato: "88"
  #iso_31662: "RU-ME"
  #type: "Республика"
}*/
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
