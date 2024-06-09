<?php

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\UI\Extension;

class ReservationField
{
    public static function GetUserTypeDescription()
    {
        return [
            'PROPERTY_TYPE' => 'E',
            'USER_TYPE' => 'iblock_reserve',
            'DESCRIPTION' => 'Запись на процедуры',
            'GetPublicEditHTML' => ['CIBlockPropertyElementList', 'GetPropertyFieldHtml'],
            'GetPublicViewHTML' => [__CLASS__, 'getPublicViewHTML']
        ];
    }

    public static function getPublicViewHTML($arProperty, $arValue)
    {
        CJSCore::Init(['popup']);
        Extension::load('ui.forms');
        Extension::load('ui.buttons');
        Extension::load('ui.buttons.icons');

        $procedureId = $arValue['VALUE'];
        $doctorId = $arProperty['ELEMENT_ID'];

        $select = ['NAME'];
        $filter = ['ID' => $procedureId];
        $result = ElementTable::getList(['select' => $select, 'filter' => $filter])->fetch();
        $title = $result['NAME'];

        $id = 'procedure_reservation' . $arValue['VALUE'];
        $html = "<a href='' id='$id'>$title</a>";

        $content = <<<CONTENT
<div class="ui-form" id="popup-reserve-container">
    <input type="hidden" id="procedure_id" value="$procedureId">
    <input type="hidden" id="doctor_id" value="$doctorId">
	<div class="ui-form-row">
		<div class="ui-form-label">
			<div class="ui-ctl-label-text">ФИО клиента</div>
		</div>
		<div class="ui-form-content">
			<div class="ui-ctl ui-ctl-textbox ui-ctl-w100">
				<input type="text" class="ui-ctl-element" id="reserve_name" placeholder="Иванов Иван">
			</div>
		</div>
	</div>
	<div class="ui-form-row">
		<div class="ui-form-label">
			<div class="ui-ctl-label-text">Выберите дату и время</div>
		</div>
		<div class="ui-form-content">
			<div class="ui-ctl ui-ctl-textbox ui-ctl-w100 ui-ctl-after-icon">
			    <div class="ui-ctl-after ui-ctl-icon-calendar"></div>
				<input type="text" onclick="BX.calendar({node: this, field: this, bTime: true, bHideTime: false});" 
				    class="ui-ctl-element" id="reserve_date">
			</div>
		</div>
	</div>
	<div class="ui-alert ui-alert-success">
        <span class="ui-alert-message">Длительность процедур - 1 час</span>
    </div>
</div>
CONTENT;
        $content = str_replace(array("\r", "\n"), '', $content);

        $js = <<<JS
BX.bind(BX('$id'), 'click', function(e) {
    BX.PreventDefault(e);
    
    let btnOptions = { 
        text: 'Записаться', 
        id: 'reserve-btn', 
        className: 'ui-btn ui-btn-success ui-btn-icon-edit',
        events: { 
            click: function() {
                let name = BX('reserve_name').value;
                let dt = BX('reserve_date').value;
                let procedure = BX('procedure_id').value;
                let doctor = BX('doctor_id').value;
                BX.ajax({ 
                    url: '/local/ajax/reserve.php', 
                    method: 'POST', 
                    data: { name, dt, procedure, doctor },
                    dataType: 'json',
                    processData: true,
                    onsuccess: function(data) {
                        if (data.error) {
                            if (BX('reserve_error')) BX('reserve_error').remove();
                            let html = '<span class="ui-alert-message">' + data.error + '</span>';
                            let props = { className: 'ui-alert ui-alert-danger' };
                            let attrs = { id: 'reserve_error' }
                            let errorDiv = BX.create('DIV', { props, html, attrs });
                            BX.append(errorDiv, BX('popup-reserve-container'));
                            BX.height(BX('popup-reserve'), 480);
                        } else {
                            window.location.href = data.href;
                        }
                    }
                });
            }
        }
    };
    let btn = new BX.PopupWindowButton(btnOptions);
    
    let popup = BX.PopupWindowManager.create('popup-reserve', null, {
        content: '$content',
        width: 400,
        height: 420,
        zIndex: 100,
        closeIcon: { opacity: 1 },
        titleBar: 'Запись на процедуру',
        closeByEsc: true,
        autoHide: true,
        overlay: { backgroundColor: '#000000', opacity: 50 },
        buttons: [btn]
    });
    popup.show();
});
JS;

        $html .= "<script>$js</script>";
        return $html;
    }
}