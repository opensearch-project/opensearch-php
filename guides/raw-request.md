# Sending Raw JSON Requests

OpenSearch client implements many high-level APIs out of the box. However, there are times when you need to send a raw
JSON request to the server. This can be done using the `request()` method of the client.

The `request()` method expects the following parameters:

| Parameter     | Description                                                                  |
| ------------- | ---------------------------------------------------------------------------- |
| `$method`     | The HTTP method to use for the request, `GET`, `POST`, `PUT`, `DELETE`, etc. |
| `$uri`        | The URI to send the request to, e.g. `/_search`.                             |
| `$attributes` | An array of request options. `body`, `params` & `options`                    |

> ![NOTE]
> The `request()` is a wrapper around the `\OpenSearch\Transport::performRequest()` method, this explains why [the
> parameters](https://github.com/opensearch-project/opensearch-php/blob/bf9b9815e9636196295432baa9c0368bc2efadd8/src/OpenSearch/Transport.php#L99)
> are intentionally similar.

Most of the time, you will only need to provide the `body` attribute. The `body` attribute is the JSON payload to send
to the server. The `params` attribute is used to set query parameters, and the `options` attribute is used to set
additional request options.

To send a raw JSON request you need to map the documentation to the `request()` method parameters. For example, [this
request](https://opensearch.org/docs/2.12/search-plugins/keyword-search/#example) that query searches for the
words `long live king` in the `shakespeare` index:

```
GET shakespeare/_search
{
  "query": {
    "match": {
      "text_entry": "long live king"
    }
  }
}
```

Can be translated to the following `request()` method call:

```php
$response = $client->request('GET', '/shakespeare/_search', [
    'body' => [
        'query' => [
            'match' => [
                'text_entry' => 'long live king'
            ]
        ]
    ]
]);
```
