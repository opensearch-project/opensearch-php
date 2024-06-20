<?php

return <<<'EOD'
    
/**
 * Alias function to getAlias()
 *
 * @deprecated added to prevent BC break introduced in 7.2.0
 * @see https://github.com/elastic/elasticsearch-php/issues/940
 */
public function getAliases(array $params = [])
{
    return $this->getAlias($params);
}
EOD;
