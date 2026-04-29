<?php

declare(strict_types=1);

namespace Atom\Cms\Web\ChangePassword;

use Atom\Cms\Service\UserService;
use Atom\Cms\Web\ChangePassword\ChangePasswordForm;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Status;
use Yiisoft\Router\UrlGeneratorInterface;
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
        $form = new ChangePasswordForm();

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->oldPassword) {
            $identity = $this->currentUser->getIdentity();
            if (!$this->userService->validatePassword($identity, $form->oldPassword)) {
                $form->addError('Incorrect password.', ['oldPassword']);
            }
        }

        if ($form->isValid()) {
            $this->userService->changePassword($identity, $form->newPassword);

            return $this->responseFactory
                ->createResponse(Status::SEE_OTHER)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('atom.cms.dashboard'),
                );
        }

        return $this->viewRenderer
            ->withLayout('@atom/cms/src/Web/Shared/Layout/Main/layout.php')
            ->render(__DIR__ . '/template', [
                'form' => $form,
            ]);
    }
}
