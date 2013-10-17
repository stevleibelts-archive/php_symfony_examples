<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-10-17
 */

//taken from: http://symfony.com/doc/current/components/http_kernel/introduction.html#installation

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

//create routes
$routes = new RouteCollection();
$routes->add(
    'hello', 
    new Route(
        '/hello/{name}', 
        array(
            '_controller' => function (Request $request) {
                return new Response(sprintf("Hello %s", $request->get('name')));
            }
        )
    )
);

//create request
$request = Request::createFromGlobals();

$matcher = new UrlMatcher($routes, new RequestContext());

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher));

$resolver = new ControllerResolver();

//setup http kernel
$kernel = new HttpKernel($dispatcher, $resolver);

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
