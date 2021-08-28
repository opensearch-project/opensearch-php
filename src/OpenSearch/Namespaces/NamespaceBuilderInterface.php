<?php

declare(strict_types=1);

/**
 * SPDX-License-Identifier: Apache-2.0
 *
 * The OpenSearch Contributors require contributions made to
 * this file be licensed under the Apache-2.0 license or a
 * compatible open source license.
 *
 * Modifications Copyright OpenSearch Contributors. See
 * GitHub history for details.
 */

namespace OpenSearch\Namespaces;

use OpenSearch\Serializers\SerializerInterface;
use OpenSearch\Transport;

interface NamespaceBuilderInterface
{
    /**
     * Returns the name of the namespace.  This is what users will call, e.g. the name
     * "foo" will be invoked by the user as `$client->foo()`
     */
    public function getName(): string;

    /**
     * Returns the actual namespace object which contains your custom methods. The transport
     * and serializer objects are provided so that your namespace may do whatever custom
     * logic is required.
     *
     * @param  Transport           $transport
     * @param  SerializerInterface $serializer
     * @return Object
     */
    public function getObject(Transport $transport, SerializerInterface $serializer);
}
