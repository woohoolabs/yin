<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;
use Devleand\Yin\JsonApi\Schema\Error\ErrorSource;

class MediaTypeUnsupported extends AbstractJsonApiException
{
    /**
     * @var string
     */
    protected $mediaTypeName;

    public function __construct(string $mediaTypeName)
    {
        parent::__construct("The media type '$mediaTypeName' is unsupported in the 'Content-Type' header!", 415);
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
                ->setSource(ErrorSource::fromParameter("content-type")),
        ];
    }

    public function getMediaTypeName(): string
    {
        return $this->mediaTypeName;
    }
}
