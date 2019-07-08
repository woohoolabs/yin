<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing;

class ResourceTypeMissingTest extends TestCase
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
    public function getMessage(): void
    {
        $exception = $this->createException();

        $message = $exception->getMessage();

        $this->assertEquals("A resource type must be included in the document!", $message);
    }

    private function createException(): ResourceTypeMissing
    {
        return new ResourceTypeMissing();
    }
}
