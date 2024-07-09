<?php

return <<<'EOD'

    /**
     * Proxy function to deletePointInTime() to prevent BC break. 
     * This API will be removed in a future version. Use 'deletePit' API instead.
     */
    public function deletePointInTime(array $params = [])
    {
        return $this->deletePit($params);
    }
EOD;
