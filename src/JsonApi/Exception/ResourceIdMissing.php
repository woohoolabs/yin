<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class ResourceIdMissing extends \Exception
{
    public function __construct()
    {
        parent::__construct("A resource ID must be included in the request!");
    }
}
