<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource;

class MediaTypeUnacceptable extends AbstractJsonApiException
{
    protected string $mediaTypeName;

    public function __construct(string $mediaTypeName)
    {
        parent::__construct("The media type '" . $mediaTypeName . "' is unacceptable in the 'Accept' header!", 406);
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
                ->setSource(ErrorSource::fromParameter("accept")),
        ];
    }

    public function getMediaTypeName(): string
    {
        return $this->mediaTypeName;
    }
}
