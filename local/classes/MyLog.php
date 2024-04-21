<?php
use Bitrix\Main\Diag\FileExceptionHandlerLog;
use Bitrix\Main\Diag\ExceptionHandlerFormatter;

class MyLog extends FileExceptionHandlerLog
{
    public function write($exception, $logType)
    {
        $text = ExceptionHandlerFormatter::format($exception);
        $context = ['type' => static::logTypeToString($logType)];
        $logLevel = static::logTypeToLevel($logType);
        $message = "OTUS - {$text}";
        $this->logger->log($logLevel, $message, $context);
    }
}