<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Hydrator\Relationship;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\ResourceIdentifier;

class ToOneRelationshipTest extends TestCase
{
    /**
     * @test
     */
    public function getResourceIdentifierWhenSetInConstructor(): void
    {
        $resourceIdentifier = (new ResourceIdentifier())->setType("user")->setId("1");

        $relationship = $this->createRelationship($resourceIdentifier);
        $this->assertEquals($resourceIdentifier, $relationship->getResourceIdentifier());
    }

    /**
     * @test
     */
    public function setResourceIdentifier(): void
    {
        $resourceIdentifier = (new ResourceIdentifier())->setType("user")->setId("1");

        $relationship = $this->createRelationship()->setResourceIdentifier($resourceIdentifier);
        $this->assertEquals($resourceIdentifier, $relationship->getResourceIdentifier());
    }

    /**
     * @test
     */
    public function isEmptyIsFalse(): void
    {
        $relationship = $this->createRelationship(new ResourceIdentifier());

        $this->assertFalse($relationship->isEmpty());
    }

    /**
     * @test
     */
    public function isEmptyIsTrue(): void
    {
        $relationship = $this->createRelationship();

        $this->assertTrue($relationship->isEmpty());
    }

    private function createRelationship(?ResourceIdentifier $resourceIdentifier = null): ToOneRelationship
    {
        return new ToOneRelationship($resourceIdentifier);
    }
}
