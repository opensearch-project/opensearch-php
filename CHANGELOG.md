# CHANGELOG
Inspired from [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)

## [Unreleased]
### Added
- Added PHP 8.2 to the CI
- Github workflow for changelog verification ([#92](https://github.com/opensearch-project/opensearch-php/pull/92))
- Added class docs generator ([#96](https://github.com/opensearch-project/opensearch-php/pull/96))
- Added support for Amazon OpenSearch Serverless SigV4 signing ([#119](https://github.com/opensearch-project/opensearch-php/pull/119))
- Added `includePortInHostHeader` option to `ClientBuilder::fromConfig` ([#118](https://github.com/opensearch-project/opensearch-php/pull/118))
- Added the `RefreshSearchAnalyzers` endpoint ([[#152](https://github.com/opensearch-project/opensearch-php/issues/152))
- Added support for `format` parameter to specify the sql response format ([#161](https://github.com/opensearch-project/opensearch-php/pull/161))
- Added ml commons model, model group and connector APIs ([#170](https://github.com/opensearch-project/opensearch-php/pull/170))
- [Feature] Send raw json request ([#171](https://github.com/opensearch-project/opensearch-php/pull/177))

### Changed

- Added Windows and macOS jobs to CI
- Added Coverage reporting to Codecov
- Support a custom signing service name for AWS SigV4 ([#117](https://github.com/opensearch-project/opensearch-php/pull/117))

### Deprecated

### Removed

### Fixed
- Updated backport helper version and add fix to backport workflow when tag is applied before closing PR ([#131](https://github.com/opensearch-project/opensearch-php/pull/131))
- Fixed host urls with trailing slash in the url ([#130](https://github.com/opensearch-project/opensearch-php/pull/140))
- Fixed point-in-time APIs ([#142](https://github.com/opensearch-project/opensearch-php/pull/142))
- Fixed bug in ClientBuilder where basic authentication is overridden by connection params ([#160](https://github.com/opensearch-project/opensearch-php/pull/160))
- Fixed PHP warning in `Connection::tryDeserializeError()` for some error responses ([#167](https://github.com/opensearch-project/opensearch-php/issues/167))

### Security


[Unreleased]: https://github.com/opensearch-project/opensearch-php/compare/2.0...HEAD
