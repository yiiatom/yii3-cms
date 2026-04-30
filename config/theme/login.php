<?php

declare(strict_types=1);

use Yiisoft\Form\Field\Checkbox;
use Yiisoft\Form\Field\CheckboxLabelPlacement;

return [
    'template' => "{input}",
    'containerClass' => 'mb-2',
    'inputClass' => 'form-control',
    'fieldConfigs' => [
        Checkbox::class => [
            'labelPlacement()' => [CheckboxLabelPlacement::SIDE],
            'addContainerClass()' => ['form-check'],
            'inputClass()' => ['form-check-input'],
            'inputLabelClass()' => ['form-check-label'],
        ],
    ],
];
