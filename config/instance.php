<?php

use Barronhsu15\InterviewTintint\Api\Handlers\OrderHandler;
use Barronhsu15\InterviewTintint\Api\ResponseBody;
use Barronhsu15\InterviewTintint\Database;
use Barronhsu15\InterviewTintint\Interfaces\DatabaseInterface;
use Barronhsu15\InterviewTintint\Logger;
use Barronhsu15\InterviewTintint\Order\OrderRepository;
use Barronhsu15\InterviewTintint\Services\OrderService;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

use function DI\autowire;
use function DI\create;
use function DI\factory;

return [
    DatabaseInterface::class => create(Database::class),

    LoggerInterface::class => create(Logger::class),

    OrderHandler::class => autowire(OrderHandler::class),

    OrderRepository::class => autowire(OrderRepository::class),

    OrderService::class => autowire(OrderService::class),

    ResponseBody::class => create(ResponseBody::class),

    ResponseInterface::class => create(Response::class),

    Router::class => factory(function () {
        $router = new Router();
        $router->setStrategy(new JsonStrategy(new ResponseFactory()));

        return $router;
    }),

    SapiEmitter::class => create(SapiEmitter::class),

    ServerRequestInterface::class => factory(fn () => ServerRequestFactory::fromGlobals()),
];
