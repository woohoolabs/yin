<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ResourceNotFound;

class ResourceNotFoundTest extends TestCase
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

    private function createException(): ResourceNotFound
    {
        return new ResourceNotFound();
    }
}
