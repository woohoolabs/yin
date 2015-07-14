<?php
namespace Src\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Src\JsonApi\Document\UsersDocument;
use Src\JsonApi\Resource\ContactResourceTransformer;
use Src\JsonApi\Resource\UserResourceTransformer;
use WoohooLabs\Yin\JsonApi\Request\Criteria;

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

        $document = new UsersDocument(
            $response,
            $resource,
            new UserResourceTransformer(new ContactResourceTransformer())
        );

        return $document->getResponse(200, new Criteria($request));
    }
}
