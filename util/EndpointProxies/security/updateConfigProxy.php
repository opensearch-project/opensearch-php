<?php

return <<<'EOD'

    /**
     * Proxy function to updateConfig() to prevent BC break.
     * This API will be removed in a future version. Use 'updateConfiguration' API instead.
     */
    public function updateConfig(array $params = [])
    {
        $body = [ 'dynamic' => $this->extractArgument($params, 'dynamic')];
        $params['body'] = $body;
        return $this->updateConfiguration($params);
    }
EOD;
