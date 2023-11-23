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

namespace OpenSearch\Endpoints\MachineLearning\Connectors;

use OpenSearch\Common\Exceptions\RuntimeException;
use OpenSearch\Endpoints\AbstractEndpoint;

class DeleteConnector extends AbstractEndpoint
{

  /**
   * @return string[]
   */
  public function getParamWhitelist(): array
  {
    return [];
  }

  /**
   * @return string
   */
  public function getURI(): string
  {
    if ($this->id) {
      return "/_plugins/_ml/connectors/$this->id";
    }

    throw new RuntimeException(
      'id is required for delete'
    );

  }

  /**
   * @return string
   */
  public function getMethod(): string
  {
    return 'DELETE';
  }
}
