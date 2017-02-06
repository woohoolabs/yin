<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;

class MediaTypeUnacceptableTest extends TestCase
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

    private function createException(string $mediaType): MediaTypeUnacceptable
    {
        return new MediaTypeUnacceptable($mediaType);
    }
}
