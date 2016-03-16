<?php
use Mte\DeepCopy\Service;

return [
    /**
     * Use: $this->getServiceLocator()->get('mteDeepCopyService_classNameAlias');
     */
    'classNameAlias' => [
        'class' => Service\Copy::class,
        'options' => [
            'alias' => 'classNameAlias',
        ]
    ],
];