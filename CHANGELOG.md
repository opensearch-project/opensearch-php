# CHANGELOG

Inspired from [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)

## [Unreleased]
### Added
### Changed
### Deprecated
### Removed
### Fixed
### Security
### Dependencies
### Updated APIs

## [2.4.1]
### Added
- Added Guzzle and Symfony client factories ([#287](https://github.com/opensearch-project/opensearch-php/pull/287))
### Changed
- Changed EndpointFactory to return new objects on each call to fix issues with parameter reusage ([#292](https://github.com/opensearch-project/opensearch-php/pull/292))

## [2.4.0]
### Added
- Generate endpoints from OpenSearch API Specification ([#194](https://github.com/opensearch-project/opensearch-php/pull/194))
- Added workflow for automated API update using OpenSearch API specification ([#209](https://github.com/opensearch-project/opensearch-php/pull/209))
- Added samples ([#218](https://github.com/opensearch-project/opensearch-php/pull/218))
- Added support for PHP 8.3 and 8.4 ([#229](https://github.com/opensearch-project/opensearch-php/pull/229))
- Added a Docker Compose config file for local development ([#245](https://github.com/opensearch-project/opensearch-php/pull/245))
- Added a test for the AWS signing client decorator ([#252](https://github.com/opensearch-project/opensearch-php/pull/252))
- Added PHPStan Deprecation rules and baseline ([#263](https://github.com/opensearch-project/opensearch-php/pull/263))
- Added PHPStan PHPUnit extensions and rules ([#263](https://github.com/opensearch-project/opensearch-php/pull/263))
- Added Guzzle and Symfony HTTP client factories ([#271](https://github.com/opensearch-project/opensearch-php/pull/271))
- Added 'colinodell/psr-testlogger' as a dev dependency ([#271](https://github.com/opensearch-project/opensearch-php/pull/271))
### Changed
- Switched to PSR Interfaces ([#233](https://github.com/opensearch-project/opensearch-php/pull/233))
- Increased PHP min version to 8.1 ([#233](https://github.com/opensearch-project/opensearch-php/pull/233))
- Increased min version of `ezimuel/ringphp` to `^1.2.2` ([225](https://github.com/opensearch-project/opensearch-php/pull/225))
- Changed fluent setters to return static ([#236](https://github.com/opensearch-project/opensearch-php/pull/236))
### Deprecated
- Passing a callable to \OpenSearch\ClientBuilder::setEndpoint() is deprecated and replaced with passing an EndpointFactory to \OpenSearch\ClientBuilder::setEndpointFactory() ([#237](https://github.com/opensearch-project/opensearch-php/pull/237))
- Connections, Connection pools and Selectors are deprecated. Use a PSR HTTP Client that supports retries instead ([#245](https://github.com/opensearch-project/opensearch-php/pull/245))
- The following namespaces have been deprecated and will be removed in 3.0.0: 'async_search', 'searchable_snapshots', 'ssl', 'data_frame_transform_deprecated', 'monitoring' ([#270](https://github.com/opensearch-project/opensearch-php/pull/270))
- A number of exceptions under `\OpenSearch\Common\Exceptions` are deprecated and moved to `\OpenSearch\Exception` ([#274](https://github.com/opensearch-project/opensearch-php/pull/274))
### Removed
- Removed support for PHP 7.3, 7.4 and 8.0 ([#233](https://github.com/opensearch-project/opensearch-php/pull/233))
- Removed support for async requests which were never actually working ([#233](https://github.com/opensearch-project/opensearch-php/pull/233))
### Fixed
- Fixed PHP 8.4 deprecations ([#229](https://github.com/opensearch-project/opensearch-php/pull/229))
- Fixed outdated tests ([#245](https://github.com/opensearch-project/opensearch-php/pull/245))
### Updated APIs
- Updated opensearch-php APIs to reflect [opensearch-api-specification@b9dcb25](https://github.com/opensearch-project/opensearch-api-specification/commit/b9dcb251d551e90ecfc416ba134efe83cbcbc1b3)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@9df46f8](https://github.com/opensearch-project/opensearch-api-specification/commit/9df46f8134641ae5b429e3e9269858c7cb27e4f0)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@592336a](https://github.com/opensearch-project/opensearch-api-specification/commit/592336afb88844f0c5785ba4b085dba3884ac580)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@799d046](https://github.com/opensearch-project/opensearch-api-specification/commit/799d04622aeddce7b697665d63a29fc049e5088e)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@1422af3](https://github.com/opensearch-project/opensearch-api-specification/commit/1422af3cddc8140fe9c3d59ee0205b278e193bb9)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@2395cb4](https://github.com/opensearch-project/opensearch-api-specification/commit/2395cb472ec5581656aac184f7b20548cd5b06ac)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@ebe0f8a](https://github.com/opensearch-project/opensearch-api-specification/commit/ebe0f8a885f7db7e882d160c101055a5aa70a707)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@398481e](https://github.com/opensearch-project/opensearch-api-specification/commit/398481e5bd1cc590d947c35379c47096f2114f00)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@6bb1fed](https://github.com/opensearch-project/opensearch-api-specification/commit/6bb1fed0a2c7cf094a5ecfdb01f0306a4b9f8eba)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@07e329e](https://github.com/opensearch-project/opensearch-api-specification/commit/07e329e8d01fd0576de6a0a3c35412fd5a9163db)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@1db1840](https://github.com/opensearch-project/opensearch-api-specification/commit/1db184063a463c5180a2cc824b1efc1aeebfd5eb)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@cb320b5](https://github.com/opensearch-project/opensearch-api-specification/commit/cb320b5482551c4f28afa26ff0d1653332699722)

## [2.3.0]

### Added

- Added a GitHub workflow for verifying CHANGELOG ([#92](https://github.com/opensearch-project/opensearch-php/pull/92))
- Added class docs generator ([#96](https://github.com/opensearch-project/opensearch-php/pull/96))
- Added support for Amazon OpenSearch Serverless SigV4 signing ([#119](https://github.com/opensearch-project/opensearch-php/pull/119))
- Added `includePortInHostHeader` option to `ClientBuilder::fromConfig` ([#118](https://github.com/opensearch-project/opensearch-php/pull/118))
- Added the `RefreshSearchAnalyzers` endpoint ([#152](https://github.com/opensearch-project/opensearch-php/issues/152))
- Added support for `format` parameter to specify the sql response format ([#161](https://github.com/opensearch-project/opensearch-php/pull/161))
- Added ml-commons model, model group and connector APIs ([#170](https://github.com/opensearch-project/opensearch-php/pull/170))
- Added support for sending raw JSON requests ([#171](https://github.com/opensearch-project/opensearch-php/pull/177))
- Added PHP 8.2 support ([#87](https://github.com/opensearch-project/opensearch-php/issues/87))
- Added Windows and MacOS support ([#100](https://github.com/opensearch-project/opensearch-php/pull/100))
- Added code coverage reporting ([#100](https://github.com/opensearch-project/opensearch-php/pull/100))
- Added support for a custom signing service name for AWS SigV4 ([#117](https://github.com/opensearch-project/opensearch-php/pull/117))
- Added support for OpenSearch 2.12 and 2.13 ([#180](https://github.com/opensearch-project/opensearch-php/pull/180))
- Added release automation to publish to packagist ([#183](https://github.com/opensearch-project/opensearch-php/pull/183))
- Added @saimedhi to opensearch-php maintainers ([#215](https://github.com/opensearch-project/opensearch-php/pull/215))

### Fixed

- Fixed backport workflow when tag is applied before closing PR ([#131](https://github.com/opensearch-project/opensearch-php/pull/131))
- Fixed host urls with trailing slash in the url ([#130](https://github.com/opensearch-project/opensearch-php/pull/140))
- Fixed point-in-time APIs ([#142](https://github.com/opensearch-project/opensearch-php/pull/142))
- Fixed basic authentication being overridden by connection params in `ClientBuilder` ([#160](https://github.com/opensearch-project/opensearch-php/pull/160))
- Fixed PHP warning in `Connection::tryDeserializeError()` for some error responses ([#167](https://github.com/opensearch-project/opensearch-php/issues/167))

[Unreleased]: https://github.com/opensearch-project/opensearch-php/compare/2.4.0...main
[2.4.0]: https://github.com/opensearch-project/opensearch-php/compare/2.3.0...2.4.0
[2.3.0]: https://github.com/opensearch-project/opensearch-php/compare/2.2.0...2.3.0
[2.2.0]: https://github.com/opensearch-project/opensearch-php/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/opensearch-project/opensearch-php/compare/2.0.3...2.1.0
[2.0.3]: https://github.com/opensearch-project/opensearch-php/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/opensearch-project/opensearch-php/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/opensearch-project/opensearch-php/compare/2.0.0...2.0.1
