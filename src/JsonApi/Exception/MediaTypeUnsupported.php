<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class MediaTypeUnsupported extends JsonApiException
{
    /**
     * @var string
     */
    private $mediaTypeName;

    /**
     * @param string $mediaTypeName
     */
    public function __construct($mediaTypeName)
    {
        parent::__construct("The media type '$mediaTypeName' is unsupported in the 'Content-Type' header!");
        $this->mediaTypeName = $mediaTypeName;
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(415)
                ->setCode("MEDIA_TYPE_UNSUPPORTED")
                ->setTitle("The provided media type is unsupported")
                ->setDetail($this->getMessage())
                ->setSource(ErrorSource::fromParameter("Content-Type"))
        ];
    }

    /**
     * @return string
     */
    public function getMediaTypeName()
    {
        return $this->mediaTypeName;
    }
}
