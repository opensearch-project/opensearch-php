<?php

return <<<'EOD'

    /**
     * Proxy function to createPointInTime() to prevent BC break. 
     * This API will be removed in a future version. Use 'createPit' API instead.
     */
    public function createPointInTime(array $params = [])
    {
        return $this->createPit($params);
    }
EOD;
