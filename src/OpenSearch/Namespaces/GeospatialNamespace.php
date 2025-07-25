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

use OpenSearch\Endpoints\Geospatial\DeleteIp2geoDatasource;
use OpenSearch\Endpoints\Geospatial\GeojsonUploadPost;
use OpenSearch\Endpoints\Geospatial\GeojsonUploadPut;
use OpenSearch\Endpoints\Geospatial\GetIp2geoDatasource;
use OpenSearch\Endpoints\Geospatial\GetUploadStats;
use OpenSearch\Endpoints\Geospatial\PutIp2geoDatasource;
use OpenSearch\Endpoints\Geospatial\PutIp2geoDatasourceSettings;

/**
 * Class GeospatialNamespace
 *
 * NOTE: This file is autogenerated using util/GenerateEndpoints.php
 */
class GeospatialNamespace extends AbstractNamespace
{
    /**
     * Delete a specific IP2Geo data source.
     *
     * $params['name']        = (string)
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function deleteIp2geoDatasource(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');

        $endpoint = $this->endpointFactory->getEndpoint(DeleteIp2geoDatasource::class);
        $endpoint->setParams($params);
        $endpoint->setName($name);

        return $this->performRequest($endpoint);
    }

    /**
     * Use an OpenSearch query to upload `GeoJSON`, operation will fail if index exists.- When type is `geo_point`, only Point geometry is allowed- When type is `geo_shape`, all geometry types are allowed (Point, MultiPoint, LineString, MultiLineString, Polygon, MultiPolygon, GeometryCollection, Envelope).
     *
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function geojsonUploadPost(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(GeojsonUploadPost::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Use an OpenSearch query to upload `GeoJSON` regardless if index exists.- When type is `geo_point`, only Point geometry is allowed- When type is `geo_shape`, all geometry types are allowed (Point, MultiPoint, LineString, MultiLineString, Polygon, MultiPolygon, GeometryCollection, Envelope).
     *
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function geojsonUploadPut(array $params = [])
    {
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(GeojsonUploadPut::class);
        $endpoint->setParams($params);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Get one or more IP2Geo data sources, defaulting to returning all if no names specified.
     *
     * $params['name']        = (array)
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getIp2geoDatasource(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');

        $endpoint = $this->endpointFactory->getEndpoint(GetIp2geoDatasource::class);
        $endpoint->setParams($params);
        $endpoint->setName($name);

        return $this->performRequest($endpoint);
    }

    /**
     * Retrieves statistics for all geospatial uploads.
     *
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function getUploadStats(array $params = [])
    {
        $endpoint = $this->endpointFactory->getEndpoint(GetUploadStats::class);
        $endpoint->setParams($params);

        return $this->performRequest($endpoint);
    }

    /**
     * Create a specific IP2Geo data source.Default values:  - `endpoint`: `"https://geoip.maps.opensearch.org/v1/geolite2-city/manifest.json"`  - `update_interval_in_days`: 3.
     *
     * $params['name']        = (string)
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function putIp2geoDatasource(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(PutIp2geoDatasource::class);
        $endpoint->setParams($params);
        $endpoint->setName($name);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

    /**
     * Update a specific IP2Geo data source.
     *
     * $params['name']        = (string)
     * $params['pretty']      = (boolean) Whether to pretty-format the returned JSON response. (Default = false)
     * $params['human']       = (boolean) Whether to return human-readable values for statistics. (Default = false)
     * $params['error_trace'] = (boolean) Whether to include the stack trace of returned errors. (Default = false)
     * $params['source']      = (string) The URL-encoded request definition. Useful for libraries that do not accept a request body for non-POST requests.
     * $params['filter_path'] = (any) A comma-separated list of filters used to filter the response. Use wildcards to match any field or part of a field's name. To exclude fields, use `-`.
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public function putIp2geoDatasourceSettings(array $params = [])
    {
        $name = $this->extractArgument($params, 'name');
        $body = $this->extractArgument($params, 'body');

        $endpoint = $this->endpointFactory->getEndpoint(PutIp2geoDatasourceSettings::class);
        $endpoint->setParams($params);
        $endpoint->setName($name);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }

}
