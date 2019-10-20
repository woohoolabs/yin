<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported;

class ClientGeneratedIdNotSupportedTest extends TestCase
{
    /**
     * @test
     */
    public function getErrors(): void
    {
        $exception = $this->createException("1");

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("403", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getClientGeneratedId(): void
    {
        $exception = $this->createException("1");

        $id = $exception->getClientGeneratedId();

        $this->assertEquals("1", $id);
    }

    private function createException(string $id): ClientGeneratedIdNotSupported
    {
        return new ClientGeneratedIdNotSupported($id);
    }
}
