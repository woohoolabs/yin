<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\InclusionUnsupported;

class InclusionUnsupportedTest extends TestCase
{
    /**
     * @test
     */
    public function getMessage(): void
    {
        $exception = $this->createException();
        $this->assertEquals("Inclusion is not supported!", $exception->getMessage());
    }

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

    private function createException(): InclusionUnsupported
    {
        return new InclusionUnsupported();
    }
}
