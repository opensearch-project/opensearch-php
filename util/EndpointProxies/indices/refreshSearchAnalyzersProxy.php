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

    $endpointBuilder = $this->endpoints;
    $endpoint = $endpointBuilder('Indices\RefreshSearchAnalyzers');
    $endpoint->setParams($params);
    $endpoint->setIndex($index);

    return $this->performRequest($endpoint);
}
EOD;
