<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class ResourceIdInvalid extends \Exception
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct("The resource ID '$id' is invalid!", 400);
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
