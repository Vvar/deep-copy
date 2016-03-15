<?php
use Mte\DeepCopy\Service;

return [
    /**
     * Use: $this->getServiceLocator()->get('mteDeepCopyService_ProjectPrototype');
     */
    'ProjectPrototype' => [
        'class' => Service\Copy::class,
        'options' => [
            'alias' => 'ProjectPrototype',
        ]
    ],
];