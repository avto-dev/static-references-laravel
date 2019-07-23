# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## v3.1.0

### Added

- Reference `CadastralRegions`

## v3.0.0

### Added

- Docker-based environment for development
- Project `Makefile`

### Changed

- Minimal `PHP` version now is `^7.1.3`
- Maximal `Laravel` version now is `5.8.x`
- Dependency `laravel/framework` changed to `illuminate/*`
- Composer scripts
- Argument and return types
- Class `\AvtoDev\StaticReferences\StaticReferencesServiceProvider` renamed to `\AvtoDev\StaticReferences\ServiceProvider`
- Service provider dependency `\Illuminate\Contracts\Foundation\Application` changed to `\Illuminate\Contracts\Container\Container`

## v2.4.0

### Changed

- Maximal PHP version now is undefined
- Maximal `laravel/framework` version now is `5.7.*`
- CI changed to [Travis CI][travis]
- [CodeCov][codecov] integrated

[travis]:https://travis-ci.org/
[codecov]:https://codecov.io/

## v2.3.1

### Added

- Added facade for reference `AutoFines`

## v2.3.0

### Added

- Reference `AutoFines`

## v2.2.0

### Changed

- CI config updated
- Required minimal PHPUnit version now `5.7.10`
- Removed unimportant PHPDoc blocks
- Code a little bit refactored
- HTML coverage report disabled by default (CI errors)

[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
