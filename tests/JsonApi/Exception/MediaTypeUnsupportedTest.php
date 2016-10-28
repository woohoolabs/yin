<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;

class MediaTypeUnsupportedTest extends TestCase
{
    /**
     * @test
     */
    public function getMediaTypeName()
    {
        $mediaType = "media-type";

        $exception = $this->createException($mediaType);
        $this->assertEquals($mediaType, $exception->getMediaTypeName());
    }

    private function createException($mediaType)
    {
        return new MediaTypeUnsupported($mediaType);
    }
}
