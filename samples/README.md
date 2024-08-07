### OpenSearch PHP Client Samples

Start an OpenSearch instance.

```
docker run -d -p 9200:9200 -p 9600:9600 -e "discovery.type=single-node" -e "OPENSEARCH_INITIAL_ADMIN_PASSWORD=myStrongPassword123!" opensearchproject/opensearch:latest
```

Run sample.

```
export OPENSEARCH_PASSWORD=myStrongPassword123!
composer install
composer run index
```
