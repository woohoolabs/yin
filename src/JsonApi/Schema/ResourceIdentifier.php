<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;

use function gettype;
use function is_array;
use function is_string;

class ResourceIdentifier
{
    use MetaTrait;

    private string $type;
    private string $id;

    /**
     * @throws JsonApiExceptionInterface
     */
    public static function fromArray(array $array, ExceptionFactoryInterface $exceptionFactory): ResourceIdentifier
    {
        if (isset($array["type"]) === false || $array["type"] === "") {
            throw $exceptionFactory->createResourceIdentifierTypeMissingException($array);
        }

        if (is_string($array["type"]) === false) {
            throw $exceptionFactory->createResourceIdentifierTypeInvalidException(gettype($array["type"]));
        }

        if (isset($array["id"]) === false || $array["type"] === "") {
            throw $exceptionFactory->createResourceIdentifierIdMissingException($array);
        }

        if (is_string($array["id"]) === false) {
            throw $exceptionFactory->createResourceIdentifierIdInvalidException(gettype($array["id"]));
        }

        $resourceIdentifier = new self();
        $resourceIdentifier->setType($array["type"]);
        $resourceIdentifier->setId($array["id"]);
        if (isset($array["meta"]) && is_array($array["meta"])) {
            $resourceIdentifier->setMeta($array["meta"]);
        }

        return $resourceIdentifier;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): ResourceIdentifier
    {
        $this->type = $type;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): ResourceIdentifier
    {
        $this->id = $id;

        return $this;
    }
}
