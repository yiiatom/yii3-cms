<?php

declare(strict_types=1);

namespace Atom\Cms\Web\Shared\Layout\Login;

use Yiisoft\Assets\AssetBundle;

final class Asset extends AssetBundle
{
    public ?string $basePath = '@assets';
    public ?string $baseUrl = '@assetsUrl';
    public ?string $sourcePath = '@atom/cms/assets';

    public array $css = [
        'bootstrap/css/bootstrap.min.css',
        'fontawesome/css/all.min.css',
        'login.css',
    ];
}
