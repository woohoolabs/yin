<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\Pagination\CursorBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\FixedPageBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\PageBasedPagination;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;
use WoohooLabs\Yin\JsonApi\Serializer\DeserializerInterface;

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
        DeserializerInterface $deserializer
    ) {
        $this->serverRequest = $request;
        $this->exceptionFactory = $exceptionFactory;
        $this->deserializer = $deserializer;
    }

    /**
     * @throws MediaTypeUnsupported|Exception
     * @return void
     */
    public function validateContentTypeHeader()
    {
        if ($this->isValidMediaTypeHeader("Content-Type") === false) {
            throw $this->exceptionFactory->createMediaTypeUnsupportedException(
                $this,
                $this->getHeaderLine("Content-Type")
            );
        }
    }

    /**
     * @throws MediaTypeUnacceptable|Exception
     * @return void
     */
    public function validateAcceptHeader()
    {
        if ($this->isValidMediaTypeHeader("Accept") === false) {
            throw $this->exceptionFactory->createMediaTypeUnacceptableException($this, $this->getHeaderLine("Accept"));
        }
    }

    /**
     * @throws QueryParamUnrecognized|Exception
     * @return void
     */
    public function validateQueryParams()
    {
        foreach ($this->getQueryParams() as $queryParamName => $queryParamValue) {
            if (preg_match("/^([a-z]+)$/", $queryParamName) &&
                in_array($queryParamName, ["fields", "include", "sort", "page", "filter"], true) === false
            ) {
                throw $this->exceptionFactory->createQueryParamUnrecognizedException($this, $queryParamName);
            }
        }
    }

    /**
     * Returns a list of media type information, extracted from a given header in the current request.
     */
    protected function isValidMediaTypeHeader(string $headerName): bool
    {
        $header = $this->getHeaderLine($headerName);
        return strpos($header, "application/vnd.api+json") === false || $header === "application/vnd.api+json";
    }

    protected function setIncludedFields()
    {
        $this->includedFields = [];
        $fields = $this->getQueryParam("fields", []);
        if (is_array($fields) === false) {
            return;
        }

        foreach ($fields as $resourceType => $resourceFields) {
            if (is_string($resourceFields)) {
                $this->includedFields[$resourceType] = array_flip(explode(",", $resourceFields));
            }
        }
    }

    public function getIncludedFields(string $resourceType): array
    {
        if ($this->includedFields === null) {
            $this->setIncludedFields();
        }

        return isset($this->includedFields[$resourceType]) ? array_keys($this->includedFields[$resourceType]) : [];
    }

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

    protected function setIncludedRelationships()
    {
        $this->includedRelationships = [];

        $includeQueryParam = $this->getQueryParam("include", "");
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

    public function hasIncludedRelationships(): bool
    {
        if ($this->includedRelationships === null) {
            $this->setIncludedRelationships();
        }

        return empty($this->includedRelationships) === false;
    }

    public function getIncludedRelationships(string $baseRelationshipPath): array
    {
        if ($this->includedRelationships === null) {
            $this->setIncludedRelationships();
        }

        if (isset($this->includedRelationships[$baseRelationshipPath])) {
            return array_values($this->includedRelationships[$baseRelationshipPath]);
        } else {
            return [];
        }
    }

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

    protected function setSorting()
    {
        $sortingQueryParam = $this->getQueryParam("sort", "");
        if ($sortingQueryParam === "") {
            $this->sorting = [];
            return;
        }

        $sorting = explode(",", $sortingQueryParam);
        $this->sorting = is_array($sorting) ? $sorting : [];
    }

    public function getSorting(): array
    {
        if ($this->sorting === null) {
            $this->setSorting();
        }

        return $this->sorting;
    }

    protected function setPagination()
    {
        $pagination =  $this->getQueryParam("page", null);
        $this->pagination = is_array($pagination) ? $pagination : [];
    }

    public function getPagination(): array
    {
        if ($this->pagination === null) {
            $this->setPagination();
        }

        return $this->pagination;
    }

    public function getFixedPageBasedPagination(int $defaultPage = null): FixedPageBasedPagination
    {
        return FixedPageBasedPagination::fromPaginationQueryParams($this->getPagination(), $defaultPage);
    }

    public function getPageBasedPagination(int $defaultPage = null, int $defaultSize = null): PageBasedPagination
    {
        return PageBasedPagination::fromPaginationQueryParams($this->getPagination(), $defaultPage, $defaultSize);
    }

    public function getOffsetBasedPagination(
        int $defaultOffset = null,
        int $defaultLimit = null
    ): OffsetBasedPagination {
        return OffsetBasedPagination::fromPaginationQueryParams($this->getPagination(), $defaultOffset, $defaultLimit);
    }

    /**
     * @param mixed $defaultCursor
     */
    public function getCursorBasedPagination($defaultCursor = null): CursorBasedPagination
    {
        return CursorBasedPagination::fromPaginationQueryParams($this->getPagination(), $defaultCursor);
    }

    protected function setFiltering()
    {
        $filtering = $this->getQueryParam("filter", []);
        $this->filtering = is_array($filtering) ? $filtering : [];
    }

    public function getFiltering(): array
    {
        if ($this->filtering === null) {
            $this->setFiltering();
        }

        return $this->filtering;
    }

    /**
     * @param mixed $default
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
     * Returns a query parameter with a name of $name if it is present in the request, or the $default value otherwise.
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

    protected function initializeParsedQueryParams()
    {
        $this->includedFields = null;
        $this->includedRelationships = null;
        $this->sorting = null;
        $this->pagination = null;
        $this->filtering = null;
    }

    /**
     * @param mixed $default
     * @return array|mixed
     */
    public function getResource($default = null)
    {
        $body = $this->getParsedBody();

        return $body["data"] ?? $default;
    }

    /**
     * @param mixed $default
     * @return string|null
     */
    public function getResourceType($default = null)
    {
        $data = $this->getResource();

        return $data["type"] ?? $default;
    }

    /**
     * @param mixed $default
     * @return string|mixed
     */
    public function getResourceId($default = null)
    {
        $data = $this->getResource();

        return isset($data["id"]) ? $data["id"] : $default;
    }

    public function getResourceAttributes(): array
    {
        $data = $this->getResource();

        return $data["attributes"] ?? [];
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function getResourceAttribute(string $attribute, $default = null)
    {
        $attributes = $this->getResourceAttributes();

        return isset($attributes[$attribute]) ? $attributes[$attribute] : $default;
    }

    /**
     * @return ToOneRelationship|null
     */
    public function getToOneRelationship(string $relationship)
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
     * @return ToManyRelationship|null
     */
    public function getToManyRelationship(string $relationship)
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

    public function getProtocolVersion()
    {
        return $this->serverRequest->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withProtocolVersion($version);

        return $self;
    }

    public function getHeaders()
    {
        return $this->serverRequest->getHeaders();
    }

    public function hasHeader($name)
    {
        return $this->serverRequest->hasHeader($name);
    }

    public function getHeader($name)
    {
        return $this->serverRequest->getHeader($name);
    }

    public function getHeaderLine($name)
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

    public function getBody()
    {
        return $this->serverRequest->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withBody($body);

        return $self;
    }

    public function getRequestTarget()
    {
        return $this->serverRequest->getRequestTarget();
    }

    public function withRequestTarget($requestTarget)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withRequestTarget($requestTarget);

        return $self;
    }

    public function getMethod()
    {
        return $this->serverRequest->getMethod();
    }

    public function withMethod($method)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withMethod($method);

        return $self;
    }

    public function getUri()
    {
        return $this->serverRequest->getUri();
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withUri($uri, $preserveHost);

        return $self;
    }

    public function getServerParams()
    {
        return $this->serverRequest->getServerParams();
    }

    public function getCookieParams()
    {
        return $this->serverRequest->getCookieParams();
    }

    public function withCookieParams(array $cookies)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withCookieParams($cookies);

        return $self;
    }

    public function getQueryParams()
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

    public function getUploadedFiles()
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
        if ($this->isParsed === false && $this->serverRequest->getParsedBody() === null) {
            $parsedBody = $this->deserializer->deserialize($this->serverRequest);
            $this->serverRequest = $this->serverRequest->withParsedBody($parsedBody);
            $this->isParsed = true;
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

    public function getAttributes()
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
