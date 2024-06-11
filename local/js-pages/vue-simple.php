<?php

use Bitrix\Main\UI\Extension;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

Extension::load('ui.vue3');
?>

<div id='application'></div>

<script>
    BX.Vue3.BitrixVue.createApp({
        data() {
            return {
                counter: 0
            }
        },
        mounted() {
            setInterval(() => {
                this.counter++
            }, 1000)
        },
        template: `
          Counter: {{ counter }}
        `
    }).mount('#application');
</script>

<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
