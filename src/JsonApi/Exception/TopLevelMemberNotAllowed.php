<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;

class TopLevelMemberNotAllowed extends AbstractJsonApiException
{
    public function __construct()
    {
        parent::__construct("If a document does not contain a top-level \"data\" key, the \"included\" member must not be present either.", 400);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("TOP_LEVEL_MEMBER_NOT_ALLOWED")
                ->setTitle("Top-level member is not allowed")
                ->setDetail("If a document does not contain a top-level \"data\" key, the \"included\" member must not be present either."),
        ];
    }
}
