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
    public function getMessage()
    {
        $exception = $this->createException();

        $this->assertEquals("Sorting is not supported!", $exception->getMessage());
    }

    private function createException(): SortingUnsupported
    {
        return new SortingUnsupported();
    }
}
