<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

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
