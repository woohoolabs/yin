<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;

class ResourceIdentifierTypeInvalid extends AbstractJsonApiException
{
    /**
     * @var string
     */
    protected $type;

    public function __construct(string $type)
    {
        parent::__construct("The resource type must be a string instead of $type!", 400);
        $this->type = $type;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("RESOURCE_IDENTIFIER_TYPE_INVALID")
                ->setTitle("Resource identifier type is invalid")
                ->setDetail("The resource type must be a string instead of $this->type!"),
        ];
    }

    public function getType(): string
    {
        return $this->type;
    }
}
