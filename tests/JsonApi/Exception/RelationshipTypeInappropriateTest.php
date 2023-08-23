<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Exception\RelationshipTypeInappropriate;

class RelationshipTypeInappropriateTest extends TestCase
{
    /**
     * @test
     */
    public function getErrors(): void
    {
        $exception = $this->createException("", "", "");

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("400", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getRelationshipName(): void
    {
        $exception = $this->createException("rel", "", "");

        $relationshipName = $exception->getRelationshipName();

        $this->assertEquals("rel", $relationshipName);
    }

    /**
     * @test
     */
    public function getCurrentRelationshipType(): void
    {
        $exception = $this->createException("", "type", "");

        $relationshipType = $exception->getCurrentRelationshipType();

        $this->assertEquals("type", $relationshipType);
    }

    /**
     * @test
     */
    public function getExpectedRelationshipType(): void
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
