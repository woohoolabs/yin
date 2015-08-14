<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Response\Traits\AcceptedTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\NoContentTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\OkTrait;

class DeleteResponse extends AbstractResponse
{
    use OkTrait;
    use AcceptedTrait;
    use NoContentTrait;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        parent::__construct($request, $response);
    }
}
