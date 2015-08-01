<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class ResourceTypeMissing extends \Exception
{
    public function __construct()
    {
        parent::__construct("A resource type must be included in the request!");
    }
}
