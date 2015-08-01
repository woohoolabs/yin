<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class ResourceTypeNotAcceptable extends \Exception
{
    /**
     * @var string
     */
    private $type;

    public function __construct($type)
    {
        parent::__construct("Resource type \"$type\" can't be accepted by Hydrator!");
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
