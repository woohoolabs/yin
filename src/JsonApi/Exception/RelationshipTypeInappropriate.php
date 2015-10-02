<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class RelationshipTypeInappropriate extends JsonApiException
{
    /**
     * @var string
     */
    protected $relationshipName;

    /**
     * @var string
     */
    protected $currentRelationshipType;

    /**
     * @var string
     */
    protected $expectedRelationshipType;

    /**
     * @param string $relationshipName
     * @param string $currentRelationshipType
     * @param string $expectedRelationshipType
     */
    public function __construct($relationshipName, $currentRelationshipType, $expectedRelationshipType)
    {
        parent::__construct(
            "The provided relationship '$relationshipName' is of type of $currentRelationshipType, but " .
            ($expectedRelationshipType ? "$expectedRelationshipType is" : "it is not the one which is") . " expected!"
        );
        $this->relationshipName = $relationshipName;
        $this->currentRelationshipType = $currentRelationshipType;
        $this->expectedRelationshipType = $expectedRelationshipType;
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(400)
                ->setCode("RELATIONSHIP_TYPE_INAPPROPRIATE")
                ->setTitle("Relationship type is inappropriate")
                ->setDetail($this->getMessage())
                ->setSource(ErrorSource::fromPointer("/data/relationships/$this->relationshipName"))
        ];
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
    public function getCurrentRelationshipType()
    {
        return $this->currentRelationshipType;
    }

    /**
     * @return string
     */
    public function getExpectedRelationshipType()
    {
        return $this->expectedRelationshipType;
    }
}
