<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipTypeInappropriate;

class RelationshipTypeInappropriateTest extends TestCase
{
    /**
     * @test
     */
    public function getErrors()
    {
        $exception = $this->createException("", "", "");

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("400", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getRelationshipName()
    {
        $exception = $this->createException("rel", "", "");

        $relationshipName = $exception->getRelationshipName();

        $this->assertEquals("rel", $relationshipName);
    }

    /**
     * @test
     */
    public function getCurrentRelationshipType()
    {
        $exception = $this->createException("", "type", "");

        $relationshipType = $exception->getCurrentRelationshipType();

        $this->assertEquals("type", $relationshipType);
    }

    /**
     * @test
     */
    public function getExpectedRelationshipType()
    {
        $exception = $this->createException("", "", "type");

        $relationshipType = $exception->getExpectedRelationshipType();

        $this->assertEquals("type", $relationshipType);
    }

    private function createException(string $name, string $type, string $expectedType): RelationshipTypeInappropriate
    {
        return new RelationshipTypeInappropriate($name, $type, $expectedType);
    }
}
