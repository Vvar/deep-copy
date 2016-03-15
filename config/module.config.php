<?php

use DeepCopy\DeepCopy;
use Mte\DeepCopy\Options\ModuleOptions;
use Mte\DeepCopy\Service\Factory;
use Mte\DeepCopy\Filter\Factory as FilterFactory;
use Mte\DeepCopy\Matcher\Factory as MatcherFactory;

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
            'deepCopy' => DeepCopy::class,
            FilterFactory::class => FilterFactory::class,
            MatcherFactory::class => MatcherFactory::class,
        ]
    ]
];