<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class MediaTypeUnacceptable extends \Exception
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

        $this->$mediaTypeName = $mediaTypeName;
    }

    /**
     * @return string
     */
    public function getMediaTypeName()
    {
        return $this->mediaTypeName;
    }
}
