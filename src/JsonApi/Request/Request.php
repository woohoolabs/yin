<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamMalformed;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\Pagination\CursorBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\FixedPageBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\PageBasedPagination;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;
use WoohooLabs\Yin\JsonApi\Serializer\DeserializerInterface;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;

class Request implements RequestInterface
{
    /**
     * @var ServerRequestInterface
     */
    protected $serverRequest;

    /**
     * @var ExceptionFactoryInterface
     */
    protected $exceptionFactory;

    /**
     * @var DeserializerInterface
     */
    protected $deserializer;

    /**
     * @var array|null
     */
    protected $includedFields;

    /**
     * @var array|null
     */
    protected $includedRelationships;

    /**
     * @var array|null
     */
    protected $sorting;

    /**
     * @var array|null
     */
    protected $pagination;

    /**
     * @var array|null
     */
    protected $filtering;

    /**
     * @var bool
     */
    protected $isParsed = false;

    public function __construct(
        ServerRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        ?DeserializerInterface $deserializer = null
    ) {
        $this->serverRequest = $request;
        $this->exceptionFactory = $exceptionFactory;
        $this->deserializer = $deserializer ?? new JsonDeserializer();
    }

    /**
     * Validates if the current request's Content-Type header conforms to the JSON:API schema.
     *
     * @throws MediaTypeUnsupported|JsonApiExceptionInterface
     */
    public function validateContentTypeHeader(): void
    {
        if ($this->isValidMediaTypeHeader("Content-Type") === false) {
            throw $this->exceptionFactory->createMediaTypeUnsupportedException(
                $this,
                $this->getHeaderLine("Content-Type")
            );
        }
    }

    /**
     * Validates if the current request's Accept header conforms to the JSON:API schema.
     *
     * @throws MediaTypeUnacceptable|JsonApiExceptionInterface
     */
    public function validateAcceptHeader(): void
    {
        if ($this->isValidMediaTypeHeader("Accept") === false) {
            throw $this->exceptionFactory->createMediaTypeUnacceptableException($this, $this->getHeaderLine("Accept"));
        }
    }

    /**
     * Validates if the current request's query parameters conform to the JSON:API schema.
     *
     * According to the JSON API specification "Implementation specific query parameters MUST
     * adhere to the same constraints as member names with the additional requirement that they
     * MUST contain at least one non a-z character (U+0061 to U+007A)".
     *
     * @throws QueryParamUnrecognized|JsonApiExceptionInterface
     */
    public function validateQueryParams(): void
    {
        foreach ($this->getQueryParams() as $queryParamName => $queryParamValue) {
            if (preg_match("/^([a-z]+)$/", $queryParamName) &&
                in_array($queryParamName, ["fields", "include", "sort", "page", "filter"], true) === false
            ) {
                throw $this->exceptionFactory->createQueryParamUnrecognizedException($this, $queryParamName);
            }
        }
    }

    protected function isValidMediaTypeHeader(string $headerName): bool
    {
        $header = $this->getHeaderLine($headerName);

        // The media type is modified with media type parameters
        if (preg_match("/application\/vnd\.api\+json\s*;\s*[a-z0-9]+/i", $header)) {
            return false;
        }

        return true;
    }

    protected function setIncludedFields(): void
    {
        $this->includedFields = [];
        $fields = $this->getQueryParam("fields", []);
        if (is_array($fields) === false) {
            throw $this->exceptionFactory->createQueryParamMalformedException($this, "fields", $fields);
        }

        foreach ($fields as $resourceType => $resourceFields) {
            if (is_string($resourceFields) === false) {
                throw $this->exceptionFactory->createQueryParamMalformedException($this, "fields", $fields);
            }

            $this->includedFields[$resourceType] = array_flip(explode(",", $resourceFields));
        }
    }

    /**
     * Returns a list of field names for the given resource type which should be present in the response.
     */
    public function getIncludedFields(string $resourceType): array
    {
        if ($this->includedFields === null) {
            $this->setIncludedFields();
        }

        return isset($this->includedFields[$resourceType]) ? array_keys($this->includedFields[$resourceType]) : [];
    }

    /**
     * Determines if a given field for a given resource type should be present in the response or not.
     */
    public function isIncludedField(string $resourceType, string $field): bool
    {
        if ($this->includedFields === null) {
            $this->setIncludedFields();
        }

        if (array_key_exists($resourceType, $this->includedFields) === false) {
            return true;
        }

        if (empty($this->includedFields[$resourceType])) {
            return false;
        }

        return isset($this->includedFields[$resourceType][$field]);
    }

    protected function setIncludedRelationships(): void
    {
        $this->includedRelationships = [];

        $includeQueryParam = $this->getQueryParam("include", "");

        if (is_string($includeQueryParam) === false) {
            throw $this->exceptionFactory->createQueryParamMalformedException($this, "include", $includeQueryParam);
        }

        if ($includeQueryParam === "") {
            return;
        }

        $relationshipNames = explode(",", $includeQueryParam);
        foreach ($relationshipNames as $relationship) {
            $relationship = ".$relationship.";
            $length = strlen($relationship);
            $dot1 = 0;

            while ($dot1 < $length - 1) {
                $dot2 = strpos($relationship, ".", $dot1 + 1);
                $path = substr($relationship, 1, $dot1 > 0 ? $dot1 - 1 : 0);
                $name = substr($relationship, $dot1 + 1, $dot2 - $dot1 - 1);

                if (isset($this->includedRelationships[$path]) === false) {
                    $this->includedRelationships[$path] = [];
                }
                $this->includedRelationships[$path][$name] = $name;

                $dot1 = $dot2;
            };
        }
    }

    /**
     * Determines if any relationship needs to be included.
     */
    public function hasIncludedRelationships(): bool
    {
        if ($this->includedRelationships === null) {
            $this->setIncludedRelationships();
        }

        return empty($this->includedRelationships) === false;
    }

    /**
     * Returns a list of relationship paths for a given parent path which should be included in the response.
     */
    public function getIncludedRelationships(string $baseRelationshipPath): array
    {
        if ($this->includedRelationships === null) {
            $this->setIncludedRelationships();
        }

        if (isset($this->includedRelationships[$baseRelationshipPath])) {
            return array_values($this->includedRelationships[$baseRelationshipPath]);
        }

        return [];
    }

    /**
     * Determines if a given relationship name that is a child of the $baseRelationshipPath should be included
     * in the response.
     */
    public function isIncludedRelationship(
        string $baseRelationshipPath,
        string $relationshipName,
        array $defaultRelationships
    ): bool {
        if ($this->includedRelationships === null) {
            $this->setIncludedRelationships();
        }

        if ($this->getQueryParam("include") === "") {
            return false;
        }

        if (empty($this->includedRelationships) && array_key_exists($relationshipName, $defaultRelationships)) {
            return true;
        }

        return isset($this->includedRelationships[$baseRelationshipPath][$relationshipName]);
    }

    /**
     * Returns the "sort[]" query parameters.
     */
    public function getSorting(): array
    {
        if ($this->sorting === null) {
            $this->setSorting();
        }

        return $this->sorting;
    }

    protected function setSorting(): void
    {
        $sortingQueryParam = $this->getQueryParam("sort", "");
        if (is_string($sortingQueryParam) === false) {
            throw $this->exceptionFactory->createQueryParamMalformedException($this, "sort", $sortingQueryParam);
        }

        if ($sortingQueryParam === "") {
            $this->sorting = [];

            return;
        }

        $sorting = explode(",", $sortingQueryParam);
        $this->sorting = is_array($sorting) ? $sorting : [];
    }

    /**
     * Returns the "page[]" query parameters.
     */
    public function getPagination(): array
    {
        if ($this->pagination === null) {
            $this->setPagination();
        }

        return $this->pagination;
    }

    protected function setPagination(): void
    {
        $pagination =  $this->getQueryParam("page", null);
        $this->pagination = is_array($pagination) ? $pagination : [];
    }

    /**
     * Returns a FixedPageBasedPagination class in order to be used for fixed page-based pagination.
     *
     * The FixedPageBasedPagination class stores the value of the "page[number]" query parameter if present
     * or the $defaultPage otherwise.
     */
    public function getFixedPageBasedPagination(?int $defaultPage = null): FixedPageBasedPagination
    {
        return FixedPageBasedPagination::fromPaginationQueryParams($this->getPagination(), $defaultPage);
    }

    /**
     * Returns a PageBasedPagination class in order to be used for page-based pagination.
     *
     * The PageBasedPagination class stores the value of the "page[number]" and "page[size]" query parameters
     * if present or the $defaultPage and $defaultSize otherwise.
     */
    public function getPageBasedPagination(?int $defaultPage = null, ?int $defaultSize = null): PageBasedPagination
    {
        return PageBasedPagination::fromPaginationQueryParams($this->getPagination(), $defaultPage, $defaultSize);
    }

    /**
     * Returns a OffsetBasedPagination class in order to be used for offset-based pagination.
     *
     * The OffsetBasedPagination class stores the value of the "page[offset]" and "page[limit]" query parameters
     * if present or the $defaultOffset and $defaultLimit otherwise.
     */
    public function getOffsetBasedPagination(
        ?int $defaultOffset = null,
        ?int $defaultLimit = null
    ): OffsetBasedPagination {
        return OffsetBasedPagination::fromPaginationQueryParams($this->getPagination(), $defaultOffset, $defaultLimit);
    }

    /**
     * Returns a CursorBasedPagination class in order to be used for cursor-based pagination.
     *
     * The CursorBasedPagination class stores the value of the "page[cursor]" query parameter if present
     * or the $defaultCursor otherwise.
     *
     * @param mixed $defaultCursor
     */
    public function getCursorBasedPagination($defaultCursor = null): CursorBasedPagination
    {
        return CursorBasedPagination::fromPaginationQueryParams($this->getPagination(), $defaultCursor);
    }

    protected function setFiltering(): void
    {
        $filtering = $this->getQueryParam("filter", []);
        $this->filtering = is_array($filtering) ? $filtering : [];
    }

    /**
     * Returns the "filter[]" query parameters.
     */
    public function getFiltering(): array
    {
        if ($this->filtering === null) {
            $this->setFiltering();
        }

        return $this->filtering;
    }

    /**
     * @param mixed|null $default
     * @return string|mixed
     */
    public function getFilteringParam(string $param, $default = null)
    {
        $filtering = $this->getFiltering();

        return $filtering[$param] ?? $default;
    }

    /**
     * @param mixed $default
     * @return array|string|mixed
     */
    public function getQueryParam(string $name, $default = null)
    {
        $queryParams = $this->serverRequest->getQueryParams();

        return isset($queryParams[$name]) ? $queryParams[$name] : $default;
    }

    /**
     * Returns a new request with the "$name" query parameter.
     *
     * @param mixed $value
     * @return $this
     */
    public function withQueryParam(string $name, $value)
    {
        $self = clone $this;
        $queryParams = $this->serverRequest->getQueryParams();
        $queryParams[$name] = $value;
        $self->serverRequest = $this->serverRequest->withQueryParams($queryParams);
        $self->initializeParsedQueryParams();

        return $self;
    }

    protected function initializeParsedQueryParams(): void
    {
        $this->includedFields = null;
        $this->includedRelationships = null;
        $this->sorting = null;
        $this->pagination = null;
        $this->filtering = null;
    }

    /**
     * Returns the primary resource if it is present in the request body, or the $default value otherwise.
     *
     * @param mixed $default
     * @return array|mixed
     */
    public function getResource($default = null)
    {
        $body = $this->getParsedBody();

        return $body["data"] ?? $default;
    }

    /**
     * Returns the "type" of the primary resource if it is present, or the $default value otherwise.
     *
     * @param mixed $default
     * @return string|mixed
     */
    public function getResourceType($default = null)
    {
        $data = $this->getResource();

        return $data["type"] ?? $default;
    }

    /**
     * Returns the "id" of the primary resource if it is present, or the $default value otherwise.
     *
     * @param mixed $default
     * @return string|mixed
     */
    public function getResourceId($default = null)
    {
        $data = $this->getResource();

        return isset($data["id"]) ? $data["id"] : $default;
    }

    /**
     * Returns the "attributes" of the primary resource.
     */
    public function getResourceAttributes(): array
    {
        $data = $this->getResource();

        return $data["attributes"] ?? [];
    }

    /**
     * Returns the $attribute attribute of the primary resource if it is present, or the $default value otherwise.
     *
     * @param mixed $default
     * @return mixed
     */
    public function getResourceAttribute(string $attribute, $default = null)
    {
        $attributes = $this->getResourceAttributes();

        return isset($attributes[$attribute]) ? $attributes[$attribute] : $default;
    }

    /**
     * Returns the $relationship to-one relationship of the primary resource if it is present, or null otherwise.
     */
    public function getToOneRelationship(string $relationship): ?ToOneRelationship
    {
        $data = $this->getResource();

        //The relationship has to exist in the request and have a data attribute to be valid
        if (isset($data["relationships"][$relationship]) &&
            array_key_exists("data", $data["relationships"][$relationship])
        ) {
            //If the data is null, this request is to clear the relationship, we return an empty relationship
            if ($data["relationships"][$relationship]["data"] === null) {
                return new ToOneRelationship();
            }
            //If the data is set and is not null, we create the relationship with a resource identifier from the request
            return new ToOneRelationship(
                ResourceIdentifier::fromArray($data["relationships"][$relationship]["data"], $this->exceptionFactory)
            );
        }

        return null;
    }

    /**
     * Returns the $relationship to-many relationship of the primary resource if it is present, or null otherwise.
     */
    public function getToManyRelationship(string $relationship): ?ToManyRelationship
    {
        $data = $this->getResource();

        if (isset($data["relationships"][$relationship]["data"]) === false) {
            return null;
        }

        $resourceIdentifiers = [];
        foreach ($data["relationships"][$relationship]["data"] as $item) {
            $resourceIdentifiers[] = ResourceIdentifier::fromArray($item, $this->exceptionFactory);
        }

        return new ToManyRelationship($resourceIdentifiers);
    }

    public function getProtocolVersion(): string
    {
        return $this->serverRequest->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withProtocolVersion($version);

        return $self;
    }

    public function getHeaders(): array
    {
        return $this->serverRequest->getHeaders();
    }

    public function hasHeader($name): bool
    {
        return $this->serverRequest->hasHeader($name);
    }

    public function getHeader($name): array
    {
        return $this->serverRequest->getHeader($name);
    }

    public function getHeaderLine($name): string
    {
        return $this->serverRequest->getHeaderLine($name);
    }

    public function withHeader($name, $value)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withHeader($name, $value);

        return $self;
    }

    public function withAddedHeader($name, $value)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withAddedHeader($name, $value);

        return $self;
    }

    public function withoutHeader($name)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withoutHeader($name);

        return $self;
    }

    public function getBody(): StreamInterface
    {
        return $this->serverRequest->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withBody($body);

        return $self;
    }

    public function getRequestTarget(): string
    {
        return $this->serverRequest->getRequestTarget();
    }

    public function withRequestTarget($requestTarget)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withRequestTarget($requestTarget);

        return $self;
    }

    public function getMethod(): string
    {
        return $this->serverRequest->getMethod();
    }

    public function withMethod($method)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withMethod($method);

        return $self;
    }

    public function getUri(): UriInterface
    {
        return $this->serverRequest->getUri();
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withUri($uri, $preserveHost);

        return $self;
    }

    public function getServerParams(): array
    {
        return $this->serverRequest->getServerParams();
    }

    public function getCookieParams(): array
    {
        return $this->serverRequest->getCookieParams();
    }

    public function withCookieParams(array $cookies)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withCookieParams($cookies);

        return $self;
    }

    public function getQueryParams(): array
    {
        return $this->serverRequest->getQueryParams();
    }

    public function withQueryParams(array $query)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withQueryParams($query);
        $self->initializeParsedQueryParams();

        return $self;
    }

    public function getUploadedFiles(): array
    {
        return $this->serverRequest->getUploadedFiles();
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withUploadedFiles($uploadedFiles);

        return $self;
    }

    public function getParsedBody()
    {
        if ($this->isParsed === false) {
            $parsedBody = $this->serverRequest->getParsedBody();
            if ($parsedBody === null || $parsedBody === []) {
                $parsedBody = $this->deserializer->deserialize($this->serverRequest);
                $this->serverRequest = $this->serverRequest->withParsedBody($parsedBody);
                $this->isParsed = true;
            }
        }

        return $this->serverRequest->getParsedBody();
    }

    public function withParsedBody($data)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withParsedBody($data);
        $this->isParsed = true;

        return $self;
    }

    public function getAttributes(): array
    {
        return $this->serverRequest->getAttributes();
    }

    public function getAttribute($name, $default = null)
    {
        return $this->serverRequest->getAttribute($name, $default);
    }

    public function withAttribute($name, $value)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withAttribute($name, $value);

        return $self;
    }

    public function withoutAttribute($name)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withoutAttribute($name);

        return $self;
    }
}
