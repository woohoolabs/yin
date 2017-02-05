<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

use Exception;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;

class ResourceIdentifier
{
    use MetaTrait;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $id;

    /**
     * @throws Exception
     */
    public static function fromArray(array $array, ExceptionFactoryInterface $exceptionFactory): ResourceIdentifier
    {
        if (isset($array["type"]) === false) {
            throw $exceptionFactory->createResourceIdentifierTypeMissing($array);
        }

        if (isset($array["id"]) === false) {
            throw $exceptionFactory->createResourceIdentifierIdMissing($array);
        }

        $resourceIdentifier = new self();
        $resourceIdentifier->setType($array["type"]);
        $resourceIdentifier->setId($array["id"]);
        if (isset($array["meta"]) === true) {
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
