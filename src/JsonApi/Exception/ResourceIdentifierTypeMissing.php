<?php
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

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(400)
                ->setCode("RESOURCE_IDENTIFIER_TYPE_MISSING")
                ->setTitle("A type for the resource identifier is missing")
                ->setDetail("A type for the resource identifier must be included!")
        ];
    }

    /**
     * @return array
     */
    public function getResourceIdentifier()
    {
        return $this->resourceIdentifier;
    }
}
