<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource;

class MediaTypeUnsupported extends AbstractJsonApiException
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
                ->setSource(ErrorSource::fromParameter("content-type")),
        ];
    }

    public function getMediaTypeName(): string
    {
        return $this->mediaTypeName;
    }
}
