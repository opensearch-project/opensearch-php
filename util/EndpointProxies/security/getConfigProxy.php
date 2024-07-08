<?php

return <<<'EOD'

/**
 * Proxy function to getConfig() to prevent BC break.
 * This API will be removed in a future version. Use 'getConfiguration' API instead.
 */
public function getConfig(array $params = [])
{
    return $this->getConfiguration($params);
}
EOD;
