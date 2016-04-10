<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipTypeInappropriate;

class RelationshipTypeNotAppropriateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getRelationshipName()
    {
        $name = "friends";

        $exception = $this->createException($name, "", "");
        $this->assertEquals($name, $exception->getRelationshipName());
    }

    /**
     * @test
     */
    public function getRelationshipType()
    {
        $type = "to-many";

        $exception = $this->createException("", $type, "");
        $this->assertEquals($type, $exception->getCurrentRelationshipType());
    }

    /**
     * @test
     */
    public function getExpectedRelationshipType()
    {
        $expectedType = "to-one";

        $exception = $this->createException("", "", $expectedType);
        $this->assertEquals($expectedType, $exception->getExpectedRelationshipType());
    }

    private function createException($name, $type, $expectedType)
    {
        return new RelationshipTypeInappropriate($name, $type, $expectedType);
    }
}
