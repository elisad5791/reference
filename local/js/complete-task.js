let Example = BX.namespace('Example');

Example.registerCompleteAction = function () {
    let createPopup = function (context) {
        let saveOptions = {
            text: 'Сохранить',
            id: 'save-btn',
            className: 'ui-btn ui-btn-success',
            events: {
                click: function() {
                    popup.close();
                    popup.destroy();
                    BX.data(context, 'custom', '');
                    context.click();
                }
            }
        };
        let buttonSave = new BX.PopupWindowButton(saveOptions);

        let popup = BX.PopupWindowManager.create('popup-comment', null, {
            content: '<div>Введите комментарий:</div><div><input type="text" id="example_comment"></div>',
            width: 300,
            height: 250,
            zIndex: 100,
            closeIcon: { opacity: 1 },
            titleBar: 'Комментарий к задаче',
            closeByEsc: true,
            autoHide: true,
            overlay: { backgroundColor: '#000000', opacity: 50 },
            buttons: [buttonSave]
        });

        popup.show();
    };

    let searchOptions = {tag: 'span', className: 'task-view-button complete'};
    let completeButton = BX.findChild(document.body, searchOptions, true);

    if (completeButton) {
        let elem = BX.create('span', {
            props: {
                className: 'task-view-button complete pause ui-btn ui-btn-success'
            },
            attrs: {
                'data-bx-id': 'task-view-b-button',
                'data-action': 'COMPLETE',
                'data-custom': 'value'
            },
            events: {
                click: function customHandler(e) {
                    let val = BX.data(this, 'custom');
                    if (val == 'value') {
                        BX.PreventDefault(e);
                        createPopup(this);
                    }
                }
            },
            text: 'Завершить'
        });
        BX.insertAfter(elem, completeButton);
        BX.remove(completeButton);
    }
};