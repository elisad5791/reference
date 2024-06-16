var Facts = BX.namespace('Facts');

Facts.showFacts = function() {
    BX.ajax({
        url: '/local/ajax/facts.php',
        method: 'GET',
        dataType: 'json',
        processData: true,
        onsuccess: function(data) {
            let popup = BX.PopupWindowManager.create('popup-facts', null, {
                content: data,
                width: 700,
                height: 600,
                zIndex: 100,
                closeIcon: { opacity: 1 },
                titleBar: 'Факты о компании',
                closeByEsc: true,
                autoHide: true,
                overlay: { backgroundColor: '#000000', opacity: 50 }
            });
            popup.show();
        }
    });
};