<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class MediaTypeUnacceptable extends JsonApiException
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
        parent::__construct("The media type '" . $mediaTypeName . "' is unacceptable in the 'Accept' header!");
        $this->mediaTypeName = $mediaTypeName;
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(406)
                ->setCode("MEDIA_TYPE_UNACCEPTABLE")
                ->setTitle("The provided media type is unacceptable")
                ->setDetail($this->getMessage())
                ->setSource(ErrorSource::fromParameter("Accept"))
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
