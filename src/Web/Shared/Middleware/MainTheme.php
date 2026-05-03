<?php

declare(strict_types=1);

namespace Atom\Web\Shared\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Form\Theme\ThemeContainer;

final readonly class MainTheme implements MiddlewareInterface
{
    public function __construct(
        private Aliases $aliases,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {

        ThemeContainer::initialize([
            'horizontal' => require $this->aliases->get('@atom/config/theme/main-horizontal.php'),
        ], 'horizontal');

        return $handler->handle($request);
    }
}
