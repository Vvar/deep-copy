<?php

use DeepCopy\DeepCopy;
use Nnx\DeepCopy\Options\ModuleOptions;
use Nnx\DeepCopy\Service\Factory;
use Nnx\DeepCopy\Filter\Factory as FilterFactory;
use Nnx\DeepCopy\Matcher\Factory as MatcherFactory;

return [
    'nnxDeepCopy' => [
        'service' => require 'service.config.php',
        'objectsCopyScheme' => require 'objectsCopyScheme.config.php',
    ],
    'service_manager' => [
        'aliases' => [
            'nnxDeepCopyOptions' => ModuleOptions::class,
        ],
        'factories' => [
            ModuleOptions::class => ModuleOptions::class,
        ],
        'abstract_factories' => [
            Factory::class
        ],
        'invokables' => [
            'deepCopy' => DeepCopy::class,
            FilterFactory::class => FilterFactory::class,
            MatcherFactory::class => MatcherFactory::class,
        ]
    ]
];