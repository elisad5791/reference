<?php

use Bitrix\Main\UI\Extension;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

Extension::load('elisad.counter');
?>

<div id="application"></div>
<script type="text/javascript">
    const counter = new BX.Counter('#application');
    counter.start();
</script>

<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');