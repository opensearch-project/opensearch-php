<?php

return <<<'EOD'

    /**
     * Proxy function to getAccount() to prevent BC break.
     * This API will be removed in a future version. Use 'getAccountDetails' API instead.
     */
    public function getAccount(array $params = [])
    {
        return $this->getAccountDetails($params);
    }
EOD;
