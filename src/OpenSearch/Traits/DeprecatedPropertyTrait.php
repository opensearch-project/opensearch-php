<?php

namespace OpenSearch\Traits;

/**
 * Provides a standard way to announce deprecated properties.
 */
trait DeprecatedPropertyTrait
{

    /**
     * Checks for access to the deprecated endpoint property.
     */
    public function __get(string $name): mixed
    {
        if ($name === 'endpoints') {
            @trigger_error('The $endpoints property is deprecated in 2.3.2 and will be removed in 3.0.0. Use $endpointFactory instead.', E_USER_DEPRECATED);
            return $this->endpoints;
        }
        return null;
    }

}
