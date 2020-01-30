<p align="center">
  <img src="https://laravel.com/assets/img/components/logo-laravel.svg" alt="Laravel" width="240" />
</p>

# Wrappers around data from `static-references-data`

[![Version][badge_packagist_version]][link_packagist]
[![Version][badge_php_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
[![Coverage][badge_coverage]][link_coverage]
[![Downloads count][badge_downloads_count]][link_packagist]
[![License][badge_license]][link_license]

Using this package you can get access to the data from package [avto-dev/static-references-data][static-references-data] simpler and more convenient.

Service-provider for integration with Laravel application comes too.

## Install

Require this package with composer using the following command:

```shell
$ composer require avto-dev/static-references-laravel "^4.0"
```

> Installed `composer` is required ([how to install composer][getcomposer]).

> You need to fix the major version of package.

## Usage

Use illuminate service container for getting access to the references instances. For example, in artisan command:

```php
<?php

namespace App\Console\Commands;

use AvtoDev\StaticReferences\References\SubjectCodes;
use AvtoDev\StaticReferences\References\VehicleCategories;

class SomeCommand extends \Illuminate\Console\Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'some:command';
    
    /**
     * Execute the console command.
     *
     * @param SubjectCodes      $subject_codes
     * @param VehicleCategories $vehicle_categories
     *
     * @return void
     */
    public function handle(SubjectCodes $subject_codes, VehicleCategories $vehicle_categories): void
    {
        // Print all vehicle categories in a one string
        $this->info(collect($vehicle_categories)->pluck('code')->implode(', ')); // A, A1, B, BE, B1, C...

        // Get all GIBDD codes for moscow subject
        $this->info($subject_codes->getByGibddCode(77)->getGibddCodes()); // [77, 97, 99, 177, ...]

        // Make GIBDD codes validation
        $subject_codes->hasGibddCode(777); // true
        $subject_codes->hasGibddCode(666); // false
    }
}
```

All available references can be found [in this directory](./src/References).

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
[static-references-data]:https://github.com/avto-dev/static-references-data
