<?php

declare(strict_types=1);

namespace Atom\Cms\Web\Shared\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Form\Theme\ThemeContainer;

class LoginTheme implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {

        ThemeContainer::initialize([
            'login' => [
                'template' => "{input}",
                'containerClass' => 'mb-2',
                'inputClass' => 'form-control',
            ],
        ], 'login');

        return $handler->handle($request);
    }
}
