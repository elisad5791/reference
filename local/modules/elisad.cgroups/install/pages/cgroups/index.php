<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$params = [
    'SEF_FOLDER' => '/crm/cgroups/',
    'SEF_URL_TEMPLATES' => ['details' => '#CGROUP_ID#/', 'edit' => '#CGROUP_ID#/edit/']
];
$APPLICATION->IncludeComponent('elisad:cgroups', '', $params);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');