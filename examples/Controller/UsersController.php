<?php
namespace WoohooLabs\Yin\Examples\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\Examples\JsonApi\Document\UsersDocument;
use WoohooLabs\Yin\Examples\JsonApi\Resource\ContactResourceTransformer;
use WoohooLabs\Yin\Examples\JsonApi\Resource\UserResourceTransformer;
use WoohooLabs\Yin\JsonApi\Request\Request;

class UsersController
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $resource = [
            [
                "id" => "1",
                "firstname" => "John",
                "lastname" => "Doe",
                "contacts" => [
                    [
                        "id" => "100",
                        "type" => "phone",
                        "value" => "+123456789"
                    ],
                    [
                        "id" => "101",
                        "type" => "email",
                        "value" => "john.doe@example.com"
                    ],
                    [
                        "id" => "102",
                        "type" => "email",
                        "value" => "secret.doe@example.com"
                    ]
                ]
            ],
            [
                "id" => "2",
                "firstname" => "Jane",
                "lastname" => "Doe",
                "contacts" => [
                    [
                        "id" => "103",
                        "type" => "email",
                        "value" => "jane.doe@example.com"
                    ]
                ]
            ]
        ];

        $document = new UsersDocument(new UserResourceTransformer(new ContactResourceTransformer()));

        return $document->getResponse($response, $resource, new Request($request));
    }
}
