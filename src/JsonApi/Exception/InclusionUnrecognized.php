<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class InclusionUnrecognized extends \Exception
{
    private $path;

    /**
     * @param string $queryParam
     */
    public function __construct($queryParam)
    {
        parent::__construct("Included path '$queryParam' can't be recognized!");
        $this->path = $queryParam;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
