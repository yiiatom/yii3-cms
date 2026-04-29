<?php

declare(strict_types=1);

namespace Atom\Cms\Web\Logout;

use Atom\Cms\Service\UserService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;

final readonly class Action
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private UserService $userService,
    ) {}

    public function __invoke(): ResponseInterface
    {
        $this->userService->logout();

        return $this->responseFactory
            ->createResponse(Status::SEE_OTHER)
            ->withHeader(
                'Location', 
                $this->urlGenerator->generate('atom.cms.dashboard'),
            );
    }
}
