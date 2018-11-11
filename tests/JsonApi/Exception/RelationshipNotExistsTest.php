<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\RelationshipNotExists;

class RelationshipNotExistsTest extends TestCase
{
    /**
     * @test
     */
    public function getErrors()
    {
        $exception = $this->createException();

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("404", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getRelationship()
    {
        $exception = $this->createException("rel");

        $relationship = $exception->getRelationship();

        $this->assertEquals("rel", $relationship);
    }

    private function createException(string $relationship = ""): RelationshipNotExists
    {
        return new RelationshipNotExists($relationship);
    }
}
