<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;

class ResourceIdentifierIdInvalid extends AbstractJsonApiException
{
    /**
     * @var string
     */
    protected $type;

    public function __construct(string $type)
    {
        parent::__construct("The resource ID must be a string instead of $type!", 400);
        $this->type = $type;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("RESOURCE_IDENTIFIER_ID_INVALID")
                ->setTitle("Resource identifier ID is invalid")
                ->setDetail("The resource ID must be a string instead of $this->type!"),
        ];
    }

    public function getType(): string
    {
        return $this->type;
    }
}
