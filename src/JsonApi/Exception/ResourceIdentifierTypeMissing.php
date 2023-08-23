<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;

class ResourceIdentifierTypeMissing extends AbstractJsonApiException
{
    /**
     * @var array
     */
    private $resourceIdentifier;

    public function __construct(array $resourceIdentifier)
    {
        parent::__construct("A type for the resource identifier must be included!", 400);
        $this->resourceIdentifier = $resourceIdentifier;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("RESOURCE_IDENTIFIER_TYPE_MISSING")
                ->setTitle("A type for the resource identifier is missing")
                ->setDetail("A type for the resource identifier must be included!"),
        ];
    }

    public function getResourceIdentifier(): array
    {
        return $this->resourceIdentifier;
    }
}
