<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class InclusionNotSupported extends \Exception
{
    public function __construct()
    {
        parent::__construct("Inclusion is not supported!");
    }
}
