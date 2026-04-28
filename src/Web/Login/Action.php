<?php

declare(strict_types=1);

namespace Atom\Cms\Web\Login;

use Atom\Cms\Repository\UserRepository;
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
        private UserRepository $userRepository,
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
        if ($form->isValid() && $this->login($form)) {
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

    private function login(LoginForm $form): bool
    {
        $identity = $this->userRepository->findOneByUsername($form->username);
        if (!$identity) {
            $form->addError('Incorrect username or password.', ['password']);
            return false;
        }

        $hasher = new PasswordHasher();
        if (!$identity->password || !$hasher->validate($form->password, $identity->password)) {
            $form->addError('Incorrect username or password.', ['password']);
            return false;
        }

        $this->currentUser->login($identity);

        return true;
    }
}
