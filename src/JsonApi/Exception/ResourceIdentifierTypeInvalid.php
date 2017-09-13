<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;

class ResourceIdentifierTypeInvalid extends JsonApiException
{
    /**
     * @var string
     */
    protected $type;

    public function __construct(string $type)
    {
        parent::__construct("The resource type '$type' must be a string!");
        $this->type = $type;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("RESOURCE_IDENTIFIER_TYPE_INVALID")
                ->setTitle("Resource identifier type is invalid")
                ->setDetail("The resource type '$this->type' must be a string!")
        ];
    }

    public function getType(): string
    {
        return $this->type;
    }
}
