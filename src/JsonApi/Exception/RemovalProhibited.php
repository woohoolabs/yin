<?php
namespace Src\WoohooLabs\Yin\JsonApi\Exception;

class RemovalProhibited extends \Exception
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
        parent::__construct("Removal of relationship '$relationshipName' is prohibited!");
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
