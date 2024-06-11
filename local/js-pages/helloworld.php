<?php

use Bitrix\Main\UI\Extension;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

Extension::load('elisad.helloworld');
?>

<div id="application"></div>

<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
