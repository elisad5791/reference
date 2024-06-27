<?php

use Bitrix\Bizproc\Activity\BaseActivity;
use Bitrix\Bizproc\FieldType;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\Activity\PropertiesDialog;

class CBPHelloWorldActivity extends BaseActivity
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->arProperties = ['Title' => '', 'Subject' => '', 'Comment' => '', 'Text' => null];
        $this->SetPropertiesTypes(['Text' => ['Type' => FieldType::STRING]]);
    }

    protected static function getFileName(): string
    {
        return __FILE__;
    }

    protected function internalExecute(): ErrorCollection
    {
        $errors = parent::internalExecute();
        $placeholders = ['#SUBJECT#' => $this->Subject, '#COMMENT#' => $this->Comment];
        $this->preparedProperties['Text'] = Loc::getMessage('HELLOWORLD_ACTIVITY_TEXT', $placeholders);
        $this->log($this->preparedProperties['Text']);
        return $errors;
    }

    public static function getPropertiesDialogMap(?PropertiesDialog $dialog = null): array
    {
        $subject = [
            'Name' => Loc::getMessage('HELLOWORLD_ACTIVITY_FIELD_SUBJECT'),
            'FieldName' => 'subject',
            'Type' => FieldType::STRING,
            'Required' => true,
            'Default' => Loc::getMessage('HELLOWORLD_ACTIVITY_DEFAULT_SUBJECT'),
            'Options' => []
        ];
        $comment = [
            'Name' => Loc::getMessage('HELLOWORLD_ACTIVITY_FIELD_COMMENT'),
            'FieldName' => 'comment',
            'Type' => FieldType::TEXT,
            'Required' => true,
            'Options' => []
        ];
        $map = ['Subject' => $subject, 'Comment' => $comment];
        return $map;
    }
}
