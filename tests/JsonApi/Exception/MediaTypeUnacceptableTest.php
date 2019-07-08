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
    public function getErrors(): void
    {
        $exception = $this->createException("");

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("406", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getMediaTypeName(): void
    {
        $exception = $this->createException("media-type");

        $mediaType = $exception->getMediaTypeName();

        $this->assertEquals("media-type", $mediaType);
    }

    private function createException(string $mediaType): MediaTypeUnacceptable
    {
        return new MediaTypeUnacceptable($mediaType);
    }
}
