<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;

class ResourceIdentifier
{
    use MetaTrait;

    /**
     * @return string
     */
    private $type;

    /**
     * @return string
     */
    private $id;

    /**
     * @param array $array
     * @param ExceptionFactoryInterface $exceptionFactory
     * @return $this
     * @throw \Exception
     */
    public static function fromArray(array $array, ExceptionFactoryInterface $exceptionFactory)
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

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return \WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
