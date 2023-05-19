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

### Security


[Unreleased]: https://github.com/opensearch-project/opensearch-php/compare/2.0...HEAD