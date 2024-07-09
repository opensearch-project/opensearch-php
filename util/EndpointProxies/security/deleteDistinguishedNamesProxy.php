<?php

return <<<'EOD'

    /**
     * Proxy function to deleteDistinguishedNames() to prevent BC break.
     * This API will be removed in a future version. Use 'deleteDistinguishedName' API instead.
     */
    public function deleteDistinguishedNames(array $params = [])
    {
        return $this->deleteDistinguishedName($params);
    }
EOD;
