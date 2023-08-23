<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Exception\ResourceIdentifierIdMissing;

class ResourceIdentifierIdMissingTest extends TestCase
{
    /**
     * @test
     */
    public function getErrors(): void
    {
        $exception = $this->createException();

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("400", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getResourceIdentifier(): void
    {
        $exception = $this->createException(["type" => "abc"]);

        $resourceIdentifier = $exception->getResourceIdentifier();

        $this->assertEquals(["type" => "abc"], $resourceIdentifier);
    }

    private function createException(array $resourceIdentifier = []): ResourceIdentifierIdMissing
    {
        return new ResourceIdentifierIdMissing($resourceIdentifier);
    }
}
