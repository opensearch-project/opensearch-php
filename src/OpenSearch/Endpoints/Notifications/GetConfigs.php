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

namespace OpenSearch\Endpoints\Notifications;

use OpenSearch\Endpoints\AbstractEndpoint;

class GetConfigs extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_plugins/_notifications/configs";
    }

    public function getParamWhitelist(): array
    {
        return [
            'last_updated_time_ms',
            'created_time_ms',
            'config_type',
            'email.email_account_id',
            'email.email_group_id_list',
            'smtp_account.method',
            'ses_account.region',
            'name',
            'name.keyword',
            'description',
            'description.keyword',
            'slack.url',
            'slack.url.keyword',
            'chime.url',
            'chime.url.keyword',
            'microsoft_teams.url',
            'microsoft_teams.url.keyword',
            'webhook.url',
            'webhook.url.keyword',
            'smtp_account.host',
            'smtp_account.host.keyword',
            'smtp_account.from_address',
            'smtp_account.from_address.keyword',
            'sns.topic_arn',
            'sns.topic_arn.keyword',
            'sns.role_arn',
            'sns.role_arn.keyword',
            'ses_account.role_arn',
            'ses_account.role_arn.keyword',
            'ses_account.from_address',
            'ses_account.from_address.keyword',
            'is_enabled',
            'email.recipient_list.recipient',
            'email.recipient_list.recipient.keyword',
            'email_group.recipient_list.recipient',
            'email_group.recipient_list.recipient.keyword',
            'query',
            'text_query',
            'pretty',
            'human',
            'error_trace',
            'source',
            'filter_path'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setBody($body): GetConfigs
    {
        if (isset($body) !== true) {
            return $this;
        }
        $this->body = $body;

        return $this;
    }
}
