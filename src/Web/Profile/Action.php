<?php

declare(strict_types=1);

namespace Atom\Cms\Web\Profile;

use Atom\Cms\Repository\UserRepository;
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
        private UserRepository $userRepository,
        private WebViewRenderer $viewRenderer,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $identity = $this->currentUser->getIdentity();
        
        $form = new ProfileForm();
        $form->username = $identity->username;
        $form->email = $identity->email;
        $form->firstName = $identity->firstName;
        $form->lastName = $identity->lastName;

        $this->formHydrator->populateFromPostAndValidate($form, $request);

        if ($form->isValid()) {
            $identity->email = $form->email;
            $identity->firstName = $form->firstName;
            $identity->lastName = $form->lastName;
            $this->userRepository->save($identity);

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
