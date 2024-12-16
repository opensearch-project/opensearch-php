# CHANGELOG

Inspired from [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)

## [Unreleased]
### Added
- Generate endpoints from OpenSearch API Specification ([#194](https://github.com/opensearch-project/opensearch-php/pull/194))
- Added workflow for automated API update using OpenSearch API specification ([#209](https://github.com/opensearch-project/opensearch-php/pull/209))
- Added samples ([#218](https://github.com/opensearch-project/opensearch-php/pull/218))
- Added support for PHP 8.3 and 8.4 ([#229](https://github.com/opensearch-project/opensearch-php/pull/229))
### Changed
- Switched to PSR Interfaces
- Increased PHP min version to 8.1
- Increased min version of `ezimuel/ringphp` to `^1.2.2`
- Changed fluent setters to return static
### Deprecated
- Passing a callable to \OpenSearch\ClientBuilder::setEndpoint() is deprecated and replaced with passing an EndpointFactory to \OpenSearch\ClientBuilder::setEndpointFactory() ([#237](https://github.com/opensearch-project/opensearch-php/pull/237))
### Removed
- Removed support for PHP 7.3 and 7.4
### Fixed
- Fixed PHP 8.4 deprecations
### Updated APIs
- Updated opensearch-php APIs to reflect [opensearch-api-specification@ebe0f8a](https://github.com/opensearch-project/opensearch-api-specification/commit/ebe0f8a885f7db7e882d160c101055a5aa70a707)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@398481e](https://github.com/opensearch-project/opensearch-api-specification/commit/398481e5bd1cc590d947c35379c47096f2114f00)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@6bb1fed](https://github.com/opensearch-project/opensearch-api-specification/commit/6bb1fed0a2c7cf094a5ecfdb01f0306a4b9f8eba)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@07e329e](https://github.com/opensearch-project/opensearch-api-specification/commit/07e329e8d01fd0576de6a0a3c35412fd5a9163db)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@1db1840](https://github.com/opensearch-project/opensearch-api-specification/commit/1db184063a463c5180a2cc824b1efc1aeebfd5eb)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@cb320b5](https://github.com/opensearch-project/opensearch-api-specification/commit/cb320b5482551c4f28afa26ff0d1653332699722)
### Security
### Dependencies

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

[Unreleased]: https://github.com/opensearch-project/opensearch-php/compare/2.3.0...main
[2.3.0]: https://github.com/opensearch-project/opensearch-php/compare/2.2.0...2.3.0
[2.2.0]: https://github.com/opensearch-project/opensearch-php/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/opensearch-project/opensearch-php/compare/2.0.3...2.1.0
[2.0.3]: https://github.com/opensearch-project/opensearch-php/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/opensearch-project/opensearch-php/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/opensearch-project/opensearch-php/compare/2.0.0...2.0.1
