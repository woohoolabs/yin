<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ResourceIdentifierTypeInvalid;

class ResourceIdentifierTypeInvalidTest extends TestCase
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
    public function getType(): void
    {
        $exception = $this->createException("integer");

        $type = $exception->getType();

        $this->assertEquals("integer", $type);
    }

    private function createException(string $type = ""): ResourceIdentifierTypeInvalid
    {
        return new ResourceIdentifierTypeInvalid($type);
    }
}
