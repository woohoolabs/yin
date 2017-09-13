<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;

class ResourceIdentifierIdInvalid extends JsonApiException
{
    /**
     * @var string
     */
    protected $id;

    public function __construct(string $id)
    {
        parent::__construct("The resource ID '$id' must be a string!");
        $this->id = $id;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("RESOURCE_IDENTIFIER_ID_INVALID")
                ->setTitle("Resource identifier ID is invalid")
                ->setDetail("The resource ID '$this->id' must be a string!")
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }
}
