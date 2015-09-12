<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;

class MediaTypeUnsupportedTest extends PHPUnit_Framework_TestCase
{
    public function testGetMediaTypeName()
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
