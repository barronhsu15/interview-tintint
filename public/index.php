<?php

namespace Barronhsu15\InterviewTintint;

use Barronhsu15\InterviewTintint\Api\Handlers\OrderHandler;
use DI\ContainerBuilder;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/instance.php');

$container = $builder->build();

$router = $container->get(Router::class);

$router->get('/orders', function () use ($container): ResponseInterface {
    return $container->call([OrderHandler::class, 'getOrdersByDatetimeAndCategory']);
});

$container->get(SapiEmitter::class)->emit($router->dispatch($container->get(ServerRequestInterface::class)));
