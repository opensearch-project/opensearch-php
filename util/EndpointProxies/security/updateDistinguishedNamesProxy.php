<?php

return <<<'EOD'

    /**
     * Proxy function to updateDistinguishedNames() to prevent BC break.
     * This API will be removed in a future version. Use 'updateDistinguishedName' API instead.
     */
    public function updateDistinguishedNames(array $params = [])
    {
        $body = [ 'nodes_dn' => $this->extractArgument($params, 'nodes_dn')];
        $params['body'] = $body;
        return $this->updateDistinguishedName($params);
    }
EOD;
