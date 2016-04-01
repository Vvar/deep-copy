<?php

use DeepCopy\DeepCopy;
use NNX\DeepCopy\Options\ModuleOptions;
use NNX\DeepCopy\Service\Factory;
use NNX\DeepCopy\Filter\Factory as FilterFactory;
use NNX\DeepCopy\Matcher\Factory as MatcherFactory;

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