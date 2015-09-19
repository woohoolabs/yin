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
     * @var string
     */
    private $expectedRelationshipType;

    /**
     * @param string $relationshipName
     * @param string $relationshipType
     * @param string $expectedRelationshipType
     */
    public function __construct($relationshipName, $relationshipType, $expectedRelationshipType)
    {
        parent::__construct(
            "The provided relationship '$relationshipName' is of type of $relationshipType, but " .
            ($expectedRelationshipType ? "$expectedRelationshipType is" : "it is not the one which is") . " expected."
        );
        $this->relationshipName = $relationshipName;
        $this->relationshipType = $relationshipType;
        $this->expectedRelationshipType = $expectedRelationshipType;
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

    /**
     * @return string
     */
    public function getExpectedRelationshipType()
    {
        return $this->expectedRelationshipType;
    }
}
