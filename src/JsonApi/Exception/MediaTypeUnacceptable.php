<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;
use Devleand\Yin\JsonApi\Schema\Error\ErrorSource;

class MediaTypeUnacceptable extends AbstractJsonApiException
{
    /**
     * @var string
     */
    protected $mediaTypeName;

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
