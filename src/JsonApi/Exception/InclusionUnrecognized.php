<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;
use Devleand\Yin\JsonApi\Schema\Error\ErrorSource;

use function implode;

class InclusionUnrecognized extends AbstractJsonApiException
{
    /**
     * @var array
     */
    protected $unrecognizedIncludes;

    public function __construct(array $unrecognizedIncludes)
    {
        parent::__construct("Included paths '" . implode(", ", $unrecognizedIncludes) . "' can't be recognized!", 400);
        $this->unrecognizedIncludes = $unrecognizedIncludes;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("INCLUSION_UNRECOGNIZED")
                ->setTitle("Inclusion is unrecognized")
                ->setDetail(
                    "Included paths '" . implode(", ", $this->unrecognizedIncludes) .
                    "' can't be recognized by the endpoint!"
                )
                ->setSource(ErrorSource::fromParameter("include")),
        ];
    }

    public function getUnrecognizedIncludes(): array
    {
        return $this->unrecognizedIncludes;
    }
}
