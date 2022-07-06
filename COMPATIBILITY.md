- [Compatibility with OpenSearch](#compatibility-with-opensearch)
- [Upgrading](#upgrading)

## Compatibility with OpenSearch

The below matrix shows the compatibility of the [`opensearch-project/opensearch-php`](https://packagist.org/packages/opensearch-project/opensearch-php) with versions of [`OpenSearch`](https://opensearch.org/downloads.html#opensearch).


|  | OpenSearch 1.0 | OpenSearch 2.0 |
| --- | --- | --- |
| Client 1.* | Y | Y * |
| Client 2.* | N | Y |

## * Upgrading

Major versions of OpenSearch introduce breaking changes that require careful upgrades of the client. While `opensearch-php-client` 2.0.0 works against the latest OpenSearch 1.x, certain deprecated features removed in OpenSearch 2.0 have also been removed from the client. Please refer to the [OpenSearch documentation](https://opensearch.org/docs/latest/clients/index/) for more information.
