<?php

return <<<'EOD'

/**
 * Proxy function to patchConfig() to prevent BC break.
 * This API will be removed in a future version. Use 'patchConfiguration' API instead.
 */
public function patchConfig(array $params = [])
{
    $ops = $this->extractArgument($params, 'ops');
    if ($ops !== null) {
        $params['body'] = $ops;
    }
    return $this->patchConfiguration($params);
}
EOD;
