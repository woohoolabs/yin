<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\SortingUnsupported;

class SortingNotSupportedTest extends TestCase
{
    /**
     * @test
     */
    public function getError()
    {
        $exception = $this->createException();

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("400", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getMessage()
    {
        $exception = $this->createException();

        $message = $exception->getMessage();

        $this->assertEquals("Sorting is not supported!", $message);
    }

    private function createException(): SortingUnsupported
    {
        return new SortingUnsupported();
    }
}
