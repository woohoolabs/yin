<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\InclusionUnrecognized;

class InclusionUnrecognizedTest extends TestCase
{
    /**
     * @test
     */
    public function getError()
    {
        $exception = $this->createException([]);

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("400", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getIncludes()
    {
        $exception = $this->createException(["a", "b", "c"]);

        $includes = $exception->getUnrecognizedIncludes();

        $this->assertEquals(["a", "b", "c"], $includes);
    }

    private function createException(array $includes): InclusionUnrecognized
    {
        return new InclusionUnrecognized($includes);
    }
}
