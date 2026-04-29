<?php

declare(strict_types=1);

namespace Atom\Cms\Web\Login;

use Atom\Cms\Service\UserService;
use Atom\Cms\Web\Login\LoginForm;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Security\PasswordHasher;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\Renderer\WebViewRenderer;

final readonly class Action
{
    public function __construct(
        private CurrentUser $currentUser,
        private FormHydrator $formHydrator,
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator,
        private UserService $userService,
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        if (!$this->currentUser->isGuest()) {
            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.cms.dashboard'),
                );
        }

        $form = new LoginForm();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->username && $form->password && !$this->userService->login($form->username, $form->password)) {
            $form->addError('Incorrect username or password.', ['password']);
        }

        if ($form->isValid()) {
            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.cms.dashboard'),
                );
        }

        return $this->viewRenderer
            ->withLayout('@atom/cms/src/Web/Shared/Layout/Login/layout.php')
            ->render(__DIR__ . '/template', [
                'form' => $form,
            ]);
    }
}
