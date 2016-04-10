<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;

class MediaTypeUnacceptableTest extends PHPUnit_Framework_TestCase
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
        return new MediaTypeUnacceptable($mediaType);
    }
}
