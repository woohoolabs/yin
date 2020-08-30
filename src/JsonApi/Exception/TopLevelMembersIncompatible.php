<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;

class TopLevelMembersIncompatible extends AbstractJsonApiException
{
    public function __construct()
    {
        parent::__construct("The members \"data\" and \"errors\" cannot coexist in the same document", 400);
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("400")
                ->setCode("TOP_LEVEL_MEMBERS_INCOMPATIBLE")
                ->setTitle("Top-level members are incompatible")
                ->setDetail("The members \"data\" and \"errors\" cannot coexist in the same document"),
        ];
    }
}
