<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;

class ResourceIdentifierTypeMissing extends JsonApiException
{
    /**
     * @var array
     */
    private $resourceIdentifier;

    public function __construct(array $resourceIdentifier)
    {
        parent::__construct("A type for the resource identifier must be included!");
        $this->resourceIdentifier = $resourceIdentifier;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("RESOURCE_IDENTIFIER_TYPE_MISSING")
                ->setTitle("A type for the resource identifier is missing")
                ->setDetail("A type for the resource identifier must be included!")
        ];
    }

    public function getResourceIdentifier(): array
    {
        return $this->resourceIdentifier;
    }
}
