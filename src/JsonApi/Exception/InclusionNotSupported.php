<?php

namespace WoohooLabs\Yin\JsonApi\Exception;

class InclusionNotSupported extends \Exception
{
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct("Inclusion is not supported from path \"$path\"!");
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
