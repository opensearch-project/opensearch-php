<?php

return <<<'EOD'

    /**
     * Proxy function to getModels() to prevent BC break. 
     * This API will be removed in a future version. Use 'searchModels' API instead.
     */
    public function getModels(array $params = [])
    {
        if (!isset($params['body'])) {
            $params['body'] = [
                'query' => [
                    'match_all' => new \StdClass(),
                ],
                'size' => 1000,
            ];
        }
        
        return $this->searchModels($params);
    }
EOD;
