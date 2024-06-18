<?php
$arComponentParameters = [
    'PARAMETERS' => [
        'SEF_FOLDER' => [
            'NAME' => 'Корневая папка',
            'DEFAULT' => '/crm/cgroups/'
        ],
        'SEF_URL_TEMPLATES' => [
            'details' => [
                'NAME' => 'Карточка группы контактов',
                'DEFAULT' => '#CGROUP_ID#/'
            ],
            'edit' => [
                'NAME' => 'Редактирование группы контактов',
                'DEFAULT' => '#CGROUP_ID#/edit/'
            ]
        ]
    ]
];