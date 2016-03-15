## mte\DeepCopy
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Vvar/deep-copy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Vvar/deep-copy/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Vvar/deep-copy/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Vvar/deep-copy/build-status/master)

Expansion module https://github.com/myclabs/DeepCopy for integration with zf2.

## Install
    add in composer.json

    ...
    "require": {
        ...
        "myclabs/deep-copy" : "dev-master",
        "mte/deep-copy" : "dev-master"
        ...
    },
    ...
    "repositories": [
    ...
        {
          "type": "git",
          "url": "https://github.com/old-town/workflow-zf2-service.git"
        },
        {
          "type": "git",
          "url": "git@github.com:Vvar/deep-copy.git"
        },
    ...
    ],

## How?

--- setting ---

    mte\deep-copy\config\objectsCopyScheme.config.php
    --------------------------------------------------------------------------------------------------------------------
    use DeepCopy\Filter\Doctrine\DoctrineCollectionFilter;
    use DeepCopy\Filter\ReplaceFilter;
    use DeepCopy\Filter\KeepFilter;
    use DeepCopy\Matcher\PropertyTypeMatcher;
    use DeepCopy\Matcher\PropertyNameMatcher;
    use DeepCopy\Matcher\PropertyMatcher;
    use myObject as Object;

    return [
        'alias_1' => [
            [
                'filter' => [
                    'class' => DoctrineCollectionFilter::class,
                    'options' => []
                ],
                'matcher' => [
                    'class' => PropertyTypeMatcher::class,
                    'options' => [
                        'objectClass' => 'Doctrine\Common\Collections\Collection',
                    ]
                ],
            ],
            [
                'filter' => new KeepFilter(),
                'matcher' => new PropertyMatcher(Object::class, 'property_1'),
            ],
        ]
        'alias_2' => [
            [
                'filter' => [
                    'class' => KeepFilter::class,
                    'options' => []
                ],
                'matcher' => [
                    'class' => PropertyMatcher::class,
                    'options' => [
                        'objectClass' => Object::class,
                        'property' => 'property_2',
                    ]
                ],
            ],
        ],
        'alias_3' => [...]
        ...
    ];
    --------------------------------------------------------------------------------------------------------------------

    'filter' takes the value as:
        value instance of DeepCopy\Filter\Filter
            or
        value instance of array['class' => string, 'options' => array]

    'matcher' takes the value as:
        value instance of DeepCopy\Matcher\Matcher
            or
        value instance of array['class' => string, 'options' => ['objectClass' => string, 'property' => string]]

--- use ---

$serviceCopy = $this->getServiceLocator()->get('mteDeepCopy_alias_1');
$oldProjectPrototype = $serviceCopy->cloneObject($myEntity, array['Additional actions when cloning']);




