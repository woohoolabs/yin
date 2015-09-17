<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class RelationshipTypeNotAppropriate extends \Exception
{
    /**
     * @var string
     */
    private $relationshipName;

    /**
     * @var string
     */
    private $relationshipType;

    /**
     * @param string $relationshipName
     * @param string $relationshipType
     */
    public function __construct($relationshipName, $relationshipType)
    {
        parent::__construct(
            "The provided relationship '$relationshipName' is of type of $relationshipType, " .
            "but it is inappropriate."
        );
        $this->relationshipName = $relationshipName;
        $this->relationshipType = $relationshipType;
    }

    /**
     * @return string
     */
    public function getRelationshipName()
    {
        return $this->relationshipName;
    }

    /**
     * @return string
     */
    public function getRelationshipType()
    {
        return $this->relationshipType;
    }
}
