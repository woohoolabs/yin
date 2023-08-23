<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Devleand\Yin\JsonApi\Schema\Error\Error;

class RequiredTopLevelMembersMissing extends AbstractJsonApiException
{
    public function __construct()
    {
        parent::__construct("A document must contain at least one of the following top-level members: \"data\", \"errors\", \"meta\"", 400);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("REQUIRED_TOP_LEVEL_MEMBERS_MISSING")
                ->setTitle("Required top-level members are missing")
                ->setDetail("A document must contain at least one of the following top-level members: \"data\", \"errors\", \"meta\""),
        ];
    }
}
