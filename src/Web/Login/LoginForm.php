<?php

declare(strict_types=1);

namespace Atom\Cms\Web\Login;

use Yiisoft\FormModel\FormModel;
use Yiisoft\Validator\Label;
use Yiisoft\Validator\Rule\Required;

final class LoginForm extends FormModel
{
    #[Label('Username')]
    #[Required]
    public ?string $username = null;

    #[Label('Password')]
    #[Required]
    public ?string $password = null;
}
