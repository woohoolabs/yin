<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipTypeInappropriate;

class RelationshipTypeNotAppropriateTest extends TestCase
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

    private function createException(string $name, string $type, string $expectedType): RelationshipTypeInappropriate
    {
        return new RelationshipTypeInappropriate($name, $type, $expectedType);
    }
}
