<?php
include_once "../vendor/autoload.php";

use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;

// Routing
$example = isset($_GET["example"]) ? $_GET["example"] : "Book";

// Invoking the controller
$className = "WoohooLabs\\Yin\\Examples\\Controller\\" . $example . "Controller";
$class = new $className();
$response = call_user_func([$class, "__invoke"], ServerRequestFactory::fromGlobals(), new Response());

// Emitting the response
$emitter = new \Zend\Diactoros\Response\SapiEmitter();
$emitter->emit($response);
