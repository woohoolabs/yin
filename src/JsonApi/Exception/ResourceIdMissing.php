<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ResourceIdMissing extends JsonApiException
{
    public function __construct()
    {
        parent::__construct("A resource ID must be included in the document!");
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(400)
                ->setCode("RESOURCE_ID_MISSING")
                ->setTitle("Resource ID is missing")
                ->setDetail("A resource ID must be included in the document!")
                ->setSource(ErrorSource::fromPointer("/data"))
        ];
    }
}
