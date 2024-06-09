<?php
use Bitrix\Main\Diag\Debug;

function deb($data, $title = '')
{
    Debug::dump($data, $title);
}