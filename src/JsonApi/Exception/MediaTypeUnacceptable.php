<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class MediaTypeUnacceptable extends JsonApiException
{
    /**
     * @var string
     */
    protected $mediaTypeName;

    public function __construct(string $mediaTypeName)
    {
        parent::__construct("The media type '" . $mediaTypeName . "' is unacceptable in the 'Accept' header!");
        $this->mediaTypeName = $mediaTypeName;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("406")
                ->setCode("MEDIA_TYPE_UNACCEPTABLE")
                ->setTitle("The provided media type is unacceptable")
                ->setDetail($this->getMessage())
                ->setSource(ErrorSource::fromParameter("Accept"))
        ];
    }

    public function getMediaTypeName(): string
    {
        return $this->mediaTypeName;
    }
}
