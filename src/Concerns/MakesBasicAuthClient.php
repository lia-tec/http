<?php

namespace LiaTec\Http\Concerns;

use LiaTec\Http\Middlewares\ChecksForExceptions;
use LiaTec\Http\Middlewares\BasicAuthMiddleware;
use LiaTec\Http\Contracts\Authorizable;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

/**
 * Se encarga de crear un cliente que trabaje con basic auth
 */
trait MakesBasicAuthClient
{
    /**
     * Factory para cliente que maneja basic auth
     *
     * @param  Authorizable              $credential
     * @param  array                     $options
     * @param  callable|MockHandler|null $mock
     * @return static
     */
    public static function basic(Authorizable $credential, $options = [], $mock = null)
    {
        $stack  = is_null($mock) ? HandlerStack::create() : HandlerStack::create($mock);
        $client = new Client(array_merge(['handler' => $stack], $options));
        $stack->push(new BasicAuthMiddleware($credential), 'basic_auth');
        $stack->push(new ChecksForExceptions(), 'checks_exceptions');

        return new static($credential, $client);
    }
}
