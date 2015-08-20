<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class InclusionUnrecognized extends \Exception
{
    /**
     * @var array
     */
    private $includes;

    /**
     * @param array $includes
     */
    public function __construct(array $includes)
    {
        parent::__construct("Included paths '" . implode(", ", $includes) . "' can't be recognized!");
        $this->includes = $includes;
    }

    /**
     * @return array
     */
    public function getIncludes()
    {
        return $this->includes;
    }
}
