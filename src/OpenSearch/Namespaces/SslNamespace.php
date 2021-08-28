<?php

declare(strict_types=1);

/**
 * SPDX-License-Identifier: Apache-2.0
 *
 * The OpenSearch Contributors require contributions made to
 * this file be licensed under the Apache-2.0 license or a
 * compatible open source license.
 *
 * Modifications Copyright OpenSearch Contributors. See
 * GitHub history for details.
 */

namespace OpenSearch\Namespaces;

use OpenSearch\Namespaces\AbstractNamespace;

/**
 * Class SslNamespace
 *
 */
class SslNamespace extends AbstractNamespace
{
    /**
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/security-api-ssl.html
     */
    public function certificates(array $params = [])
    {
        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Ssl\Certificates');
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }
}
