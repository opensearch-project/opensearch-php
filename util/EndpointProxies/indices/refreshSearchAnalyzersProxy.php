<?php

return <<<'EOD'
    
/**
 * $params['index']              = (list) A comma-separated list of index names to refresh analyzers for
 *
 * @param array $params Associative array of parameters
 * @return array
 */
public function refreshSearchAnalyzers(array $params = [])
{
    $index = $this->extractArgument($params, 'index');

    $endpoint = $this->endpointFactory->getEndpoint(\OpenSearch\Endpoints\Indices\RefreshSearchAnalyzers::class);
    $endpoint->setParams($params);
    $endpoint->setIndex($index);

    return $this->performRequest($endpoint);
}
EOD;
