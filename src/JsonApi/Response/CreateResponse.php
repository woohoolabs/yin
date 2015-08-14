<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Response\Traits\AcceptedTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\ConflictTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\CreatedTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\ForbiddenTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\NoContentTrait;

class CreateResponse extends AbstractResponse
{
    use CreatedTrait;
    use AcceptedTrait;
    use NoContentTrait;
    use ForbiddenTrait;
    use ConflictTrait;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        parent::__construct($request, $response);
    }
}
