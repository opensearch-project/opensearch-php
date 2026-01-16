# CHANGELOG

Inspired from [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)

## [Unreleased]
### Added
### Changed
### Deprecated
### Removed
### Fixed
### Security
### Updated APIs

## [2.5.1]
### Added
- Support Symfony 8 ([#381](https://github.com/opensearch-project/opensearch-php/pull/381))
### Fixed
- Retries in logging message in GuzzleRetryDecider are off by one ([#386](https://github.com/opensearch-project/opensearch-php/pull/386))
- Fix newlines in docs ([#382](https://github.com/opensearch-project/opensearch-php/issues/382))
- Allow URL params with the name `type` ([#384](https://github.com/opensearch-project/opensearch-php/issues/384))
### Updated APIs
- Updated opensearch-php APIs to reflect [opensearch-api-specification@2954600](https://github.com/opensearch-project/opensearch-api-specification/commit/2954600ddafbd98a1ab9f530054bb1b62380a92a)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@0fbd80c](https://github.com/opensearch-project/opensearch-api-specification/commit/0fbd80c66e905d91a290c0edc741eed43f8c4e7f)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@6124008](https://github.com/opensearch-project/opensearch-api-specification/commit/61240083b635ae29340cc41e631311686cce8158)

## [2.5.0]
### Changed
- Moved duplicate health check workflow step to a shared action  ([#366](https://github.com/opensearch-project/opensearch-php/pull/366))
- Bump composer dependencies and add `--prefer-lowest` to the test matrix  ([#367](https://github.com/opensearch-project/opensearch-php/pull/367))
- Upgrade from phpunit v9 to v10 ([#371](https://github.com/opensearch-project/opensearch-php/pull/371))
- Simplify logic for url param encoding ([#370](https://github.com/opensearch-project/opensearch-php/pull/370))
### Fixed
- ID is being double encoded ([#360](https://github.com/opensearch-project/opensearch-php/issues/360))
### Updated APIs
- Updated opensearch-php APIs to reflect [opensearch-api-specification@db59af1](https://github.com/opensearch-project/opensearch-api-specification/commit/db59af1556fced5193c61a768d7e5153976acd5d)

## [2.4.6]
### Added
- Added support for injecting Guzzle middleware ([#353](https://github.com/opensearch-project/opensearch-php/pull/354))
### Changed
- Switch from deprecated tibdex/github-app-token to official actions/create-github-app-token [#362](https://github.com/opensearch-project/opensearch-php/pull/362)
- Update OpenSearch version test matrix [#361](https://github.com/opensearch-project/opensearch-php/pull/361)
### Fixed
- Fixed scrolling triggers deprecation error ([#163](https://github.com/opensearch-project/opensearch-php/issues/163), [#356](https://github.com/opensearch-project/opensearch-php/pull/356))
### Updated APIs
- Updated opensearch-php APIs to reflect [opensearch-api-specification@c7f7cff](https://github.com/opensearch-project/opensearch-api-specification/commit/c7f7cff38852ceecf613fe027893bdc34443297c)

## [2.4.5]
### Fixed
- Fixed double encoding of index ([#348](https://github.com/opensearch-project/opensearch-php/issues/348))

## [2.4.4]
### Added
- Added URL encoding to all endpoint parameters ([#335](https://github.com/opensearch-project/opensearch-php/pull/335))
### Fixed
- Reduce distribution size by actualizing .gitattributes' export ignore section ([#330](https://github.com/opensearch-project/opensearch-php/pull/330))
- Fix error when content_type is NULL ([#345](https://github.com/opensearch-project/opensearch-php/pull/345))
### Updated APIs
- Updated opensearch-php APIs to reflect [opensearch-api-specification@8cb2e8f](https://github.com/opensearch-project/opensearch-api-specification/commit/8cb2e8fc639fbc78f6850b24ee29b6b8e44c6492)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@a8ea65d](https://github.com/opensearch-project/opensearch-api-specification/commit/a8ea65db455a2752f250df5694fa1e2195b57b87)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@acebe7b](https://github.com/opensearch-project/opensearch-api-specification/commit/acebe7b352b08feaa3ccfbd300514e143cff61d8)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@d4eab1a](https://github.com/opensearch-project/opensearch-api-specification/commit/d4eab1a2e59db2b28e58a83df29bd72fc99c71b4)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@3d086c0](https://github.com/opensearch-project/opensearch-api-specification/commit/3d086c000f24551662dfb99dc9f9b647edfabf61)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@bddc88a](https://github.com/opensearch-project/opensearch-api-specification/commit/bddc88aedae99e8497ba85b08926e19e87a33ef0)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@0e87c7c](https://github.com/opensearch-project/opensearch-api-specification/commit/0e87c7ca0676ddfe03d419b985e08031742f5b62)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@0d01e0d](https://github.com/opensearch-project/opensearch-api-specification/commit/0d01e0d9d2f95acfb5a8eccef4c1b3d2178d338d)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@fc5e888](https://github.com/opensearch-project/opensearch-api-specification/commit/fc5e888f8b7c1a09ca0c718c1dcb8bbca48c0a37)

## [2.4.3]
### Added
- Added `auth_aws` option to GuzzleClientFactory and SymfonyClientFactory ([#314](https://github.com/opensearch-project/opensearch-php/pull/314))
### Changed
- Updated Client constructor to make EndpointFactory an optional parameter ([#315](https://github.com/opensearch-project/opensearch-php/pull/315))
### Fixed
- Fixed checking for content type in JSON deserialization ([#318](https://github.com/opensearch-project/opensearch-php/issues/318))
- Fixed mismatch in return types between `Client::performRequest()` and `Transport::sendRequest()` ([#307](https://github.com/opensearch-project/opensearch-php/issues/307))
- Fixed legacy client options being passed as headers ([#301](https://github.com/opensearch-project/opensearch-php/issues/301))
- Fixed endpoint options not being passed to legacy transport ([#296](https://github.com/opensearch-project/opensearch-php/issues/296))
### Updated APIs
- Updated opensearch-php APIs to reflect [opensearch-api-specification@89cd8f3](https://github.com/opensearch-project/opensearch-api-specification/commit/89cd8f36a17a452e16307261969537107ba54b0b)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@5ed668d](https://github.com/opensearch-project/opensearch-api-specification/commit/5ed668d81b34ae90c22a605755fe1c340f38c27d)

## [2.4.2]
### Changed
- Update user guide with new factory approach ([#257](https://github.com/opensearch-project/opensearch-php/issues/257))
### Fixed
- Fixed deprecated class instantiation warnings ([#283](https://github.com/opensearch-project/opensearch-php/issues/283))
- Fixed async requests ([#297](https://github.com/opensearch-project/opensearch-php/issues/297))
### Updated APIs
- Updated opensearch-php APIs to reflect [opensearch-api-specification@5697cbd](https://github.com/opensearch-project/opensearch-api-specification/commit/5697cbd37a824f756ec6579e5cb812bd06ceee53)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@22483a2](https://github.com/opensearch-project/opensearch-api-specification/commit/22483a2bdfe1022611b1de7db5f45af9289a8654)

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
- Updated opensearch-php APIs to reflect [opensearch-api-specification@5697cbd](https://github.com/opensearch-project/opensearch-api-specification/commit/5697cbd37a824f756ec6579e5cb812bd06ceee53)
- Updated opensearch-php APIs to reflect [opensearch-api-specification@22483a2](https://github.com/opensearch-project/opensearch-api-specification/commit/22483a2bdfe1022611b1de7db5f45af9289a8654)
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

[Unreleased]: https://github.com/opensearch-project/opensearch-php/compare/2.4.5...main
[2.4.5]: https://github.com/opensearch-project/opensearch-php/compare/2.4.4...2.4.5
[2.4.4]: https://github.com/opensearch-project/opensearch-php/compare/2.4.3...2.4.4
[2.4.3]: https://github.com/opensearch-project/opensearch-php/compare/2.4.2...2.4.3
[2.4.2]: https://github.com/opensearch-project/opensearch-php/compare/2.4.1...2.4.2
[2.4.1]: https://github.com/opensearch-project/opensearch-php/compare/2.4.0...2.4.1
[2.4.0]: https://github.com/opensearch-project/opensearch-php/compare/2.3.0...2.4.0
[2.3.0]: https://github.com/opensearch-project/opensearch-php/compare/2.2.0...2.3.0
[2.2.0]: https://github.com/opensearch-project/opensearch-php/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/opensearch-project/opensearch-php/compare/2.0.3...2.1.0
[2.0.3]: https://github.com/opensearch-project/opensearch-php/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/opensearch-project/opensearch-php/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/opensearch-project/opensearch-php/compare/2.0.0...2.0.1
