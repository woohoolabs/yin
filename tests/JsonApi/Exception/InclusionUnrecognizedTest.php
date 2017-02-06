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
    public function getIncludes()
    {
        $includes = ["a", "b", "c"];

        $exception = $this->createException($includes);
        $this->assertEquals($includes, $exception->getUnrecognizedIncludes());
    }

    private function createException(array $includes): InclusionUnrecognized
    {
        return new InclusionUnrecognized($includes);
    }
}
