<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Exception\RequiredTopLevelMembersMissing;
use WoohooLabs\Yin\JsonApi\Exception\TopLevelMemberNotAllowed;
use WoohooLabs\Yin\JsonApi\Exception\TopLevelMembersIncompatible;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;
use WoohooLabs\Yin\JsonApi\Serializer\DeserializerInterface;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;

use function array_flip;
use function array_key_exists;
use function array_keys;
use function array_values;
use function explode;
use function in_array;
use function is_array;
use function is_string;
use function preg_match;
use function strlen;
use function strpos;
use function strtolower;
use function substr;
use function trim;

class JsonApiRequest extends AbstractRequest implements JsonApiRequestInterface
{
    /**
     * @var ExceptionFactoryInterface
     */
    protected $exceptionFactory;

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
     * @var array|null
     */
    protected $profiles;

    public function __construct(
        ServerRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        ?DeserializerInterface $deserializer = null
    ) {
        parent::__construct($request, $deserializer ?? new JsonDeserializer());
        $this->exceptionFactory = $exceptionFactory;
    }

    /**
     * Validates if the current request's Content-Type header conforms to the JSON:API schema.
     *
     * @throws MediaTypeUnsupported|JsonApiExceptionInterface
     */
    public function validateContentTypeHeader(): void
    {
        if ($this->isValidMediaTypeHeader("content-type") === false) {
            throw $this->exceptionFactory->createMediaTypeUnsupportedException(
                $this,
                $this->getHeaderLine("content-type")
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
        if ($this->isValidMediaTypeHeader("accept") === false) {
            throw $this->exceptionFactory->createMediaTypeUnacceptableException($this, $this->getHeaderLine("accept"));
        }
    }

    /**
     * Validates if the current request's query parameters conform to the JSON:API schema.
     *
     * According to the JSON:API specification "Implementation specific query parameters MUST
     * adhere to the same constraints as member names with the additional requirement that they
     * MUST contain at least one non a-z character (U+0061 to U+007A)".
     *
     * @throws QueryParamUnrecognized|JsonApiExceptionInterface
     */
    public function validateQueryParams(): void
    {
        foreach ($this->getQueryParams() as $queryParamName => $queryParamValue) {
            if (
                preg_match("/^([a-z]+)$/", $queryParamName) === 1 &&
                in_array($queryParamName, ["fields", "include", "sort", "page", "filter", "profile"], true) === false
            ) {
                throw $this->exceptionFactory->createQueryParamUnrecognizedException($this, $queryParamName);
            }
        }
    }

    /**
     * Validates if the current request's top-level members conform to the JSON:API schema.
     *
     * According to the JSON:API specification:
     * - A document MUST contain at least one of the following top-level members: "data", "errors", "meta".
     * - The members "data" and "errors" MUST NOT coexist in the same document.
     * - The document MAY contain any of these top-level members: "jsonapi", "links", "included"
     * - If a document does not contain a top-level "data" key, the "included" member MUST NOT be present either.
     * @throws RequiredTopLevelMembersMissing|TopLevelMembersIncompatible|TopLevelMemberNotAllowed|JsonApiExceptionInterface
     */
    public function validateTopLevelMembers(): void
    {
        $body = (array) $this->getParsedBody();

        if (isset($body["data"]) === false && isset($body["errors"]) === false && isset($body["meta"]) === false) {
            throw $this->exceptionFactory->createRequiredTopLevelMembersMissingException($this);
        }

        if (isset($body["data"]) && isset($body["errors"])) {
            throw $this->exceptionFactory->createTopLevelMembersIncompatibleException($this);
        }

        if (isset($body["data"]) === false && isset($body["included"])) {
            throw $this->exceptionFactory->createTopLevelMemberNotAllowedException($this);
        }
    }

    protected function isValidMediaTypeHeader(string $headerName): bool
    {
        $header = $this->getHeaderLine($headerName);

        // The media type is modified by media type parameters
        $matches = [];
        $isMatching = preg_match("/^.*application\/vnd\.api\+json\s*;\s*([A-Za-z0-9]+)\s*=.*$/i", $header, $matches);

        return $isMatching === 0 || (isset($matches[1]) && strtolower($matches[1]) === "profile");
    }

    protected function setAppliedProfiles(): void
    {
        $this->setHeaderProfiles("applied", "content-type");
    }

    protected function setRequestedProfiles(): void
    {
        $this->setHeaderProfiles("requested", "accept");
    }

    protected function setRequiredProfiles(): void
    {
        $this->setQueryParamProfiles("required", "profile");
    }

    protected function setHeaderProfiles(string $key, string $headerName): void
    {
        $header = $this->getHeaderLine($headerName);

        $matches = [];

        preg_match("/^.*application\/vnd\.api\+json\s*;\s*profile\s*=\s*[\"]*([^\";,]*).*$/i", $header, $matches);

        if (isset($matches[1]) === false) {
            $this->profiles[$key] = [];
            return;
        }

        $this->profiles[$key] = array_flip(explode(" ", $matches[1]));
    }

    protected function setQueryParamProfiles(string $key, string $queryParamName): void
    {
        $queryParam = $this->getQueryParam($queryParamName, "");

        if (is_string($queryParam) === false) {
            throw $this->exceptionFactory->createQueryParamMalformedException($this, $queryParamName, $queryParam);
        }

        $queryParam = trim($queryParam);
        if ($queryParam === "") {
            $this->profiles[$key] = [];
            return;
        }

        $this->profiles[$key] = array_flip(explode(" ", $queryParam));
    }

    /**
     * @return string[]
     */
    public function getRequestedProfiles(): array
    {
        if (isset($this->profiles["requested"]) === false) {
            $this->setRequestedProfiles();
        }

        return array_keys($this->profiles["requested"]);
    }

    public function isProfileRequested(string $profile): bool
    {
        if (isset($this->profiles["requested"]) === false) {
            $this->setRequestedProfiles();
        }

        return isset($this->profiles["requested"][$profile]);
    }

    /**
     * @return string[]
     */
    public function getRequiredProfiles(): array
    {
        if (isset($this->profiles["required"]) === false) {
            $this->setRequiredProfiles();
        }

        return array_keys($this->profiles["required"]);
    }

    public function isProfileRequired(string $profile): bool
    {
        if (isset($this->profiles["required"]) === false) {
            $this->setRequiredProfiles();
        }

        return isset($this->profiles["required"][$profile]);
    }

    /**
     * @return string[]
     */
    public function getAppliedProfiles(): array
    {
        if (isset($this->profiles["applied"]) === false) {
            $this->setAppliedProfiles();
        }

        return array_keys($this->profiles["applied"]);
    }

    public function isProfileApplied(string $profile): bool
    {
        if (isset($this->profiles["applied"]) === false) {
            $this->setAppliedProfiles();
        }

        return isset($this->profiles["applied"][$profile]);
    }

    protected function setIncludedFields(): array
    {
        $includedFields = [];
        $fields = $this->getQueryParam("fields", []);
        if (is_array($fields) === false) {
            throw $this->exceptionFactory->createQueryParamMalformedException($this, "fields", $fields);
        }

        foreach ($fields as $resourceType => $resourceFields) {
            if (is_string($resourceFields) === false) {
                throw $this->exceptionFactory->createQueryParamMalformedException($this, "fields", $fields);
            }

            $includedFields[$resourceType] = array_flip(explode(",", $resourceFields));
        }

        return $includedFields;
    }

    /**
     * Returns a list of field names for the given resource type which should be present in the response.
     */
    public function getIncludedFields(string $resourceType): array
    {
        if ($this->includedFields === null) {
            $this->includedFields = $this->setIncludedFields();
        }

        return isset($this->includedFields[$resourceType]) ? array_keys($this->includedFields[$resourceType]) : [];
    }

    /**
     * Determines if a given field for a given resource type should be present in the response or not.
     */
    public function isIncludedField(string $resourceType, string $field): bool
    {
        if ($this->includedFields === null) {
            $this->includedFields = $this->setIncludedFields();
        }

        if (array_key_exists($resourceType, $this->includedFields) === false) {
            return true;
        }

        if (isset($this->includedFields[$resourceType][""])) {
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
                $pos = strpos($relationship, ".", $dot1 + 1);
                $dot2 = $pos !== false ? $pos : 0;
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
            $this->sorting = $this->setSorting();
        }

        return $this->sorting;
    }

    protected function setSorting(): array
    {
        $sortingQueryParam = $this->getQueryParam("sort", "");
        if (is_string($sortingQueryParam) === false) {
            throw $this->exceptionFactory->createQueryParamMalformedException($this, "sort", $sortingQueryParam);
        }

        if ($sortingQueryParam === "") {
            return [];
        }

        return explode(",", $sortingQueryParam);
    }

    /**
     * Returns the "page[]" query parameters.
     */
    public function getPagination(): array
    {
        if ($this->pagination === null) {
            $this->pagination = $this->setPagination();
        }

        return $this->pagination;
    }

    protected function setPagination(): array
    {
        $pagination = $this->getQueryParam("page", []);

        if (is_array($pagination) === false) {
            throw $this->exceptionFactory->createQueryParamMalformedException($this, "page", $pagination);
        }

        return $pagination;
    }

    /**
     * Returns the "filter[]" query parameters.
     */
    public function getFiltering(): array
    {
        if ($this->filtering === null) {
            $this->filtering = $this->setFiltering();
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

    protected function setFiltering(): array
    {
        $filtering = $this->getQueryParam("filter", []);

        if (is_array($filtering) === false) {
            throw $this->exceptionFactory->createQueryParamMalformedException($this, "filter", $filtering);
        }

        return $filtering;
    }

    /**
     * Returns the primary resource if it is present in the request body, or the $default value otherwise.
     *
     * @param mixed $default
     * @return array|mixed
     */
    public function getResource($default = null)
    {
        $body = (array) $this->getParsedBody();

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

        return $data["id"] ?? $default;
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

        return $attributes[$attribute] ?? $default;
    }

    public function hasToOneRelationship(string $relationship): bool
    {
        $data = $this->getResource();

        return isset($data["relationships"][$relationship]) && array_key_exists("data", $data["relationships"][$relationship]);
    }

    /**
     * Returns the $relationship to-one relationship of the primary resource if it is present, or null otherwise.
     */
    public function getToOneRelationship(string $relationship): ToOneRelationship
    {
        $data = $this->getResource();

        // The relationship has to exist in the request and have a data attribute to be valid
        if (isset($data["relationships"][$relationship]) && array_key_exists("data", $data["relationships"][$relationship])) {
            // If the data is null, this request is to clear the relationship, we return an empty relationship
            if ($data["relationships"][$relationship]["data"] === null) {
                return new ToOneRelationship();
            }
            // If the data is set and is not null, we create the relationship with a resource identifier from the request
            return new ToOneRelationship(
                ResourceIdentifier::fromArray($data["relationships"][$relationship]["data"], $this->exceptionFactory)
            );
        }

        throw $this->exceptionFactory->createRelationshipNotExistsException($relationship);
    }

    public function hasToManyRelationship(string $relationship): bool
    {
        $data = $this->getResource();

        return isset($data["relationships"][$relationship]["data"]);
    }

    /**
     * Returns the $relationship to-many relationship of the primary resource if it is present, or null otherwise.
     */
    public function getToManyRelationship(string $relationship): ToManyRelationship
    {
        $data = $this->getResource();

        if (isset($data["relationships"][$relationship]["data"]) === false) {
            throw $this->exceptionFactory->createRelationshipNotExistsException($relationship);
        }

        $resourceIdentifiers = [];
        foreach ($data["relationships"][$relationship]["data"] as $item) {
            $resourceIdentifiers[] = ResourceIdentifier::fromArray($item, $this->exceptionFactory);
        }

        return new ToManyRelationship($resourceIdentifiers);
    }

    protected function headerChanged(string $name): void
    {
        $name = strtolower($name);

        if ($name === "content-type") {
            $this->profiles["applied"] = null;
        }

        if ($name === "accept") {
            $this->profiles["requested"] = null;
        }
    }

    protected function queryParamChanged(string $name): void
    {
        if ($name === "fields") {
            $this->includedFields = null;
        }

        if ($name === "include") {
            $this->includedRelationships = null;
        }

        if ($name === "sort") {
            $this->sorting = null;
        }

        if ($name === "page") {
            $this->pagination = null;
        }

        if ($name === "filter") {
            $this->filtering = null;
        }

        if ($name === "profile") {
            $this->profiles["required"] = null;
        }
    }
}
