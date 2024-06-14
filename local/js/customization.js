let Customization = BX.namespace('Customization');

Customization.addTaskButton = function () {
    let searchOptions = {tag: 'a', className: 'task-view-button edit'};
    let editButton = BX.findChild(document.body, searchOptions, true);

    if (editButton) {
        let href = window.location.href;

        let matches = href.match(/\/task\/view\/([\d]+)\//i);
        let taskId = 0;
        if (matches) {
            taskId = matches[1];
        }
        let symb = href.indexOf('?') === -1 ? '?' : '&';
        let newHref = href + symb + 'task=' + taskId + '&' + 'pdf=1&sessid=' + BX.bitrix_sessid();

        let elem = BX.create('a', {
            props: {
                className: 'task-view-button edit ui-btn ui-btn-link',
                href: newHref,
            },
            text: 'Скачать как PDF'
        });
        BX.insertAfter(elem, editButton);
    }
};

Customization.addTaskMenu = function () {
    BX.addCustomEvent('onPopupFirstShow', function (popup) {
        if (popup.uniquePopupId === 'menu-popup-task-view-b') {
            let menu = BX.PopupMenu.getMenuById('task-view-b');
            let href = window.location.href;
            let matches = href.match(/\/task\/view\/([\d]+)\//i);
            let taskId = 0;
            if (matches) {
                taskId = matches[1];
            }
            let symb = href.indexOf('?') === -1 ? '?' : '&';
            let newHref = href + symb + 'task=' + taskId + '&' + 'pdf=1&sessid=' + BX.bitrix_sessid();

            menu.addMenuItem({
                text: 'Скачать как PDF',
                href: newHref,
                className: 'menu-popup-item-create'
            });
        }
    });
};