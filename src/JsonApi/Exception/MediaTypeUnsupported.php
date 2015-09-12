<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class MediaTypeUnsupported extends \Exception
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
        parent::__construct("The media type '$mediaTypeName' is unsupported in the Content-Type header!");
        $this->mediaTypeName = $mediaTypeName;
    }

    /**
     * @return string
     */
    public function getMediaTypeName()
    {
        return $this->mediaTypeName;
    }
}
