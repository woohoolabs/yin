<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;

class MediaTypeUnsupportedTest extends TestCase
{
    /**
     * @test
     */
    public function getError()
    {
        $exception = $this->createException("");

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("415", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getMediaTypeName()
    {
        $exception = $this->createException("media-type");

        $mediaTypeName = $exception->getMediaTypeName();

        $this->assertEquals("media-type", $mediaTypeName);
    }

    private function createException(string $mediaType): MediaTypeUnsupported
    {
        return new MediaTypeUnsupported($mediaType);
    }
}
