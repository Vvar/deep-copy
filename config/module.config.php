<?php

use DeepCopy\DeepCopy;
use Mte\DeepCopy\Options\ModuleOptions;
use Mte\DeepCopy\Service\Factory;

return [
    'mteDeepCopy' => [
        'service' => require 'service.config.php',
        'objectsCopyScheme' => require 'objectsCopyScheme.config.php',
    ],
    'service_manager' => [
        'aliases' => [
            'mteDeepCopyOptions' => ModuleOptions::class,
        ],
        'factories' => [
            ModuleOptions::class => ModuleOptions::class,
        ],
        'abstract_factories' => [
            Factory::class
        ],
        'invokables' => [
            'deepCopy' => DeepCopy::class
        ]
    ]
];