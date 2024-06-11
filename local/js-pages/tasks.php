<?php

use Bitrix\Main\UI\Extension;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

Extension::load('elisad.tasks');
?>

<div id="application"></div>
<script type="text/javascript">
    const taskManager = new BX.TaskManager('#application');
    taskManager.start();
</script>

<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');