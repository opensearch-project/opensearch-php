<?php

return <<<'EOD'

/**
 * Proxy function to updateDistinguishedNames() to prevent BC break.
 * This API will be removed in a future version. Use 'updateDistinguishedName' API instead.
 */
public function updateDistinguishedNames(array $params = [])
{
    return $this->updateDistinguishedName($params);
}
EOD;
