# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## UNRELEASED

### Removed

- Unused dependency `tarampampam/wrappers-php`

## v4.0.0

### Changed

- Minimal `illuminate\*` packages version now is `^5.5`
- Maximal `illuminate\*` packages version now is `~6.0`
- Interface `References\ReferenceInterface` now extends `IteratorAggregate, Countable, Illuminate\Contracts\Support\Arrayable` and not contains any methods
- Package service-provider don't use cache for performance optimization
- Performance improvements in `has*` methods _(indexes used)_

### Added

- New reference implementations:
  - `References\CadastralDistricts`
  - `References\SubjectCodes`
  - `References\VehicleCategories`
  - `References\VehicleFineArticles`
  - `References\VehicleRegistrationActions`
  - `References\VehicleRepairMethods`
- New reference:
  - `References\VehicleTypes`
- New interface:
  - `References\Entities\EntityInterface` (extends `Illuminate\Contracts\Support\Support\Arrayable`)
- New entity classes:
  - `References\Entities\CadastralArea`
  - `References\Entities\CadastralDistrict`
  - `References\Entities\SubjectCodesInfo`
  - `References\Entities\VehicleCategory`
  - `References\Entities\VehicleFineArticle`
  - `References\Entities\VehicleRegistrationAction`
  - `References\Entities\VehicleRepairMethod`
  - `References\Entities\VehicleType`
- GitHub actions for a tests running

### Removed

- Facades (`AutoCategoriesFacade`, `AutoFinesFacade`, `AutoRegionsFacade`, `CadastralRegionsFacade`, `RegistrationActionsFacade`, `RepairMethodsFacade`)
- Classes:
  - `References\AbstractReference`
  - `References\AbstractReferenceEntry`
  - `References\AutoCategories\AutoCategories`
  - `References\AutoCategories\AutoCategoryEntry`
  - `References\AutoFines\AutoFineEntry`
  - `References\AutoFines\AutoFines`
  - `References\AutoRegions\AutoRegionEntry`
  - `References\AutoRegions\AutoRegions`
  - `References\CadastralDistricts\CadastralDistrictEntry`
  - `References\CadastralDistricts\CadastralDistricts`
  - `References\CadastralDistricts\CadastralRegionEntry`
  - `References\CadastralDistricts\CadastralRegions`
  - `References\RegistrationActions\RegistrationActionEntry`
  - `References\RegistrationActions\RegistrationActions`
  - `References\RepairMethods\RepairMethods`
  - `References\RepairMethods\RepairMethodsEntry`
- Interfaces:
  - `References\ReferenceEntryInterface`
- Traits:
  - `References\Traits\TransliterateTrait`
  
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
