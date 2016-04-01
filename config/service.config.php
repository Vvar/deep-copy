<?php
use NNX\DeepCopy\Service;

return [
    /**
     * Use: $this->getServiceLocator()->get('nnxDeepCopyService_classNameAlias');
     */
    'classNameAlias' => [
        'class' => Service\Copy::class,
        'options' => [
            'alias' => 'classNameAlias',
        ]
    ],
];