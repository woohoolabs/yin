<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Response\Traits\AcceptedTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\ConflictTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\ForbiddenTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\NoContentTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\NotFoundTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\OkTrait;

class UpdateResponse extends AbstractResponse
{
    use OkTrait;
    use AcceptedTrait;
    use NoContentTrait;
    use ForbiddenTrait;
    use NotFoundTrait;
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
