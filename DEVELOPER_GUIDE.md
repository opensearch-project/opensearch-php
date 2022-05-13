- [Developer Guide](#developer-guide)
  - [Getting Started](#getting-started)
    - [Git Clone OpenSearch PHP Client Repository](#git-clone-opensearch-php-client-repository)
    - [Install Prerequisites](#install-prerequisites)
      - [PHP 7.3 or higher](#php-73-or-higher)
    - [Unit Testing](#unit-testing)
    - [Integration Testing](#integration-testing)
    - [Static analyse and code style checker](#static-analyse-and-code-style-checker)
# Developer Guide

So you want to contribute code to the OpenSearch PHP Client? Excellent! We're glad you're here. Here's what you need to do:

## Getting Started

### Git Clone OpenSearch PHP Client Repository

Fork [opensearch-project/opensearch-php](https://github.com/opensearch-project/opensearch-php) and clone locally,
e.g. `git clone https://github.com/[your username]/opensearch-php.git`.

### Install Prerequisites

#### PHP 7.3 or higher

OpenSearch PHP Client builds using [PHP](https://php.net) 7.3 at a minimum.

### Unit Testing

PHPUnit is used for testing, and it can be simplified run with `composer run unit`

### Integration Testing

In order to test opensearch-php client, you need a running OpenSearch server. 
If you don't have a running server, you can start one with Docker using `docker run -p 9200:9200 -p 9600:9600 -e "discovery.type=single-node" opensearchproject/opensearch:latest` 

The integration tests are using by default following address `https://admin:admin@localhost:9200`. This can be changed by setting the environment variable `OPENSEARCH_URL` to a different url.

To run the integration tests, you can use `composer run integration`

### Static analyse and code style checker

The project uses PhpStan for static analyse and php-cs-fixer for code style checker. You can use both tools with following codes

```bash
composer run phpstan
composer run php-cs
```