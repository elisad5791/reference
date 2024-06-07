let Elisad = BX.namespace('Elisad');

Elisad.helloWorld = function () {
    alert(BX.message('ELISAD_HELLO_WORLD'));
};

Elisad.createProfileButton = function () {
    let profileTitle = BX('pagetitle');
    let className = 'ui-btn ui-btn-xs ui-btn-success ui-btn-icon-setting';
    let options = { props: { type: 'button', className }, html: 'Открыть попап' };
    let profileButton = BX.create('button', options);
    BX.bind(profileButton, 'click', Elisad.showProfilePopup);
    profileTitle.appendChild(profileButton);
};

Elisad.showProfilePopup = function() {
    let saveOptions = {
        text: 'Сохранить',
        id: 'save-btn',
        className: 'ui-btn ui-btn-success',
        events: { click: Elisad.helloWorld }
    };
    let copyOptions = { text: 'Копировать', id: 'copy-btn', className: 'ui-btn ui-btn-primary' };
    let buttonSave = new BX.PopupWindowButton(saveOptions);
    let buttonCopy = new BX.PopupWindowButton(copyOptions);

    let popup = BX.PopupWindowManager.create('popup-profile',null, {
        content: 'Контент, отображаемый в теле окна',
        width: 600,
        height: 300,
        zIndex: 100,
        closeIcon: { opacity: 1 },
        titleBar: 'Окно в профиле',
        closeByEsc: true,
        darkMode: false,
        autoHide: false,
        draggable: true,
        resizable: true,
        lightShadow: true,
        angle: false,
        overlay: { backgroundColor: '#000000', opacity: 50 },
        buttons: [buttonSave, buttonCopy]
    });

    popup.show();
};

Elisad.registerDiskEvent = function() {
    BX.addCustomEvent('onDiskUploadPopupShow', Elisad.addItemToPopup);
};

Elisad.addItemToPopup = function() {
    let menu = document.querySelector('#popup-window-content-menu-popup-popupMenuAdd .menu-popup-items');
    let className = 'menu-popup-item menu-popup-no-icon';
    let html = `
        <span class="menu-popup-item-icon"></span>
        <span class="menu-popup-item-text">Шаблон</span>
    `;
    let options = { props: { className }, html };
    let newItem = BX.create('span', options);
    menu.appendChild(newItem);
};

Elisad.modifyKanbanCard = function() {
    let interval = setInterval(function() {
        let cards = document.querySelectorAll('.main-kanban-item');
        if (cards.length > 0) {
            clearInterval(interval);
            cards.forEach(function(card) {
                let id = BX.data(card, 'id');
                if (id > 0) {
                    let html = 'ID: ' + id;
                    let newItem = BX.create('div', { html });
                    let className = 'crm-kanban-item-date';
                    let dateField = BX.findChild(card, { tag: 'div', className }, true);
                    BX.insertAfter(newItem, dateField);
                }
            });
        }
    }, 100);
};