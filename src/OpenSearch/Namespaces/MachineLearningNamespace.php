<?php

declare(strict_types=1);

/**
 * Copyright OpenSearch Contributors
 * SPDX-License-Identifier: Apache-2.0
 *
 * Elasticsearch PHP client
 *
 * @link      https://github.com/elastic/elasticsearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */

namespace OpenSearch\Namespaces;

use OpenSearch\Namespaces\AbstractNamespace;

/**
 * Class ConnectorsNamespace
 */
class MachineLearningNamespace extends AbstractNamespace {

  /**
   * $params['body']             = (string) The connector configuration (Required)
   *
   * @param array $params Associative array of parameters
   *
   * @return string
   *   The connector id.
   */
  public function createConnector(array $params = []): string {
    $body = $this->extractArgument($params, 'body');
    $endpointBuilder = $this->endpoints;
    $endpoint = $endpointBuilder('MachineLearning\CreateConnector');
    $endpoint->setParams($params);
    $endpoint->setBody($body);

    return $this->performRequest($endpoint);
  }

}
