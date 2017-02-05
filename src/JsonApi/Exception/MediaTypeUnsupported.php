<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class MediaTypeUnsupported extends JsonApiException
{
    /**
     * @var string
     */
    protected $mediaTypeName;

    public function __construct(string $mediaTypeName)
    {
        parent::__construct("The media type '$mediaTypeName' is unsupported in the 'Content-Type' header!");
        $this->mediaTypeName = $mediaTypeName;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("415")
                ->setCode("MEDIA_TYPE_UNSUPPORTED")
                ->setTitle("The provided media type is unsupported")
                ->setDetail($this->getMessage())
                ->setSource(ErrorSource::fromParameter("Content-Type"))
        ];
    }

    public function getMediaTypeName(): string
    {
        return $this->mediaTypeName;
    }
}
