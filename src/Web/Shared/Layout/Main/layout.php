<?php

declare(strict_types=1);

use Atom\Cms\Web\Shared\Layout\Main\Asset;
use Yiisoft\Html\Html;

$assetManager->register(Asset::class);

$this->addCssFiles($assetManager->getCssFiles());
$this->addCssStrings($assetManager->getCssStrings());
$this->addJsFiles($assetManager->getJsFiles());
$this->addJsStrings($assetManager->getJsStrings());
$this->addJsVars($assetManager->getJsVars());

$this->beginPage()
?>
<!DOCTYPE html>
<html lang="<?= Html::encode($applicationParams->locale) ?>">
<head>
    <meta charset="<?= Html::encode($applicationParams->charset) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= $aliases->get('@baseUrl/favicon.svg') ?>" type="image/svg+xml">
    <title><?= Html::encode($this->getTitle()) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<aside class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
    <?= Html::a('<span class="fs-4">Atom</span>')
        ->encode(false)
        ->url($urlGenerator->generate('atom.cms.dashboard'))
        ->class('d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none') ?>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <?= Html::a('<i class="fa-solid fa-tachograph-digital"></i> Dashboard')
                ->encode(false)
                ->url($urlGenerator->generate('atom.cms.dashboard'))
                ->class('nav-link active') ?>
        </li>
    </ul>
    <hr>
    <div class="dropdown current-user">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="avatar">
                <?php if ($url = $userService->getAvatarUrl()): ?>
                    <img src="<?= $url ?>" alt="">
                <?php else: ?>
                    <i class="fa-regular fa-user"></i>
                <?php endif; ?>
            </div>
            <strong><?= Html::encode($userService->getDisplayName()) ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li>
                <?= Html::a('Change password')
                    ->url($urlGenerator->generate('atom.cms.change-password'))
                    ->class('dropdown-item') ?>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <?= Html::a('Log out')
                    ->url($urlGenerator->generate('atom.cms.logout'))
                    ->class('dropdown-item') ?>
            </li>
        </ul>
    </div>
</aside>

<main class="mx-3">
    <?= $content ?>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
