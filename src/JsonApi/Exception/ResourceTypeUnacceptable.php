<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class ResourceTypeUnacceptable extends \Exception
{
    /**
     * @var string
     */
    private $type;

    public function __construct($type)
    {
        parent::__construct("Resource type '$type' can't be accepted by the Hydrator!");
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
