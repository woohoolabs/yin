<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class SortingNotSupported extends \Exception
{
    public function __construct()
    {
        parent::__construct("Sorting is not supported!");
    }
}
