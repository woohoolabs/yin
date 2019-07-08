<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ResourceIdentifierTypeMissing;

class ResourceIdentifierTypeMissingTest extends TestCase
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
        $exception = $this->createException(["id" => "1"]);

        $resourceIdentifier = $exception->getResourceIdentifier();

        $this->assertEquals(["id" => "1"], $resourceIdentifier);
    }

    private function createException(array $resourceIdentifier = []): ResourceIdentifierTypeMissing
    {
        return new ResourceIdentifierTypeMissing($resourceIdentifier);
    }
}
