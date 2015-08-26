<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class FullReplacementProhibited extends \Exception
{
    /**
     * @var string
     */
    private $relationshipName;

    /**
     * @param string $relationshipName
     */
    public function __construct($relationshipName)
    {
        parent::__construct("Full replacement of relationship '$relationshipName' is prohibited!");
        $this->relationshipName = $relationshipName;
    }

    /**
     * @return string
     */
    public function getRelationshipName()
    {
        return $this->relationshipName;
    }
}
