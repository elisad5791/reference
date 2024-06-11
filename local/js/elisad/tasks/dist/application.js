/* eslint-disable */
(function (exports,ui_vue3,main_core) {
	'use strict';

	var Item = {
	  props: {
	    position: {
	      "default": 0
	    },
	    text: {
	      "default": ''
	    }
	  },
	  template: "\n      <div class=\"taskmanager-list-item\">{{position}}. {{text}}</div>\n    "
	};

	var TaskList = {
	  components: {
	    Item: Item
	  },
	  data: function data() {
	    return {
	      list: []
	    };
	  },
	  methods: {
	    addNew: function addNew() {
	      var result = prompt('Какую задачу вы планируете выполнить?');
	      this.list.push(result);
	    },
	    close: function close() {
	      this.$Bitrix.Application.get().detachTemplate();
	    }
	  },
	  template: "\n\t\t<div class=\"taskmanager-list\">\n\t\t\t<div class=\"taskmanager-list-title\">\u0421\u043F\u0438\u0441\u043E\u043A \u0437\u0430\u0434\u0430\u0447 \u043D\u0430 \u0441\u0435\u0433\u043E\u0434\u043D\u044F</div>\n\t\t\t<template v-for=\"(value, index) in list\" :key=\"index\">\n\t\t\t\t<Item :position=\"index+1\" :text=\"value\"/>\n\t\t\t</template>\n\t\t\t<template v-if=\"list.length <= 0\">\n\t\t\t  \t<div class=\"taskmanager-list-empty\">\u041D\u0430 \u0441\u0435\u0433\u043E\u0434\u043D\u044F \u0437\u0430\u0434\u0430\u0447 \u043D\u0435\u0442</div>\n\t\t\t</template>\n\t\t\t<div class=\"taskmanager-list-buttons\">\n\t\t\t\t<button @click=\"addNew\">\u0414\u043E\u0431\u0430\u0432\u0438\u0442\u044C \u0437\u0430\u0434\u0430\u0447\u0443</button>\n\t\t\t\t<button @click=\"close\">\u0417\u0430\u043A\u0440\u044B\u0442\u044C \u043A\u043E\u043C\u043F\u043E\u043D\u0435\u043D\u0442</button>\n\t\t\t</div>\n\t\t</div>\n\t"
	};

	function _classPrivateFieldInitSpec(obj, privateMap, value) { _checkPrivateRedeclaration(obj, privateMap); privateMap.set(obj, value); }
	function _checkPrivateRedeclaration(obj, privateCollection) { if (privateCollection.has(obj)) { throw new TypeError("Cannot initialize the same private elements twice on an object"); } }
	var _application = /*#__PURE__*/new WeakMap();
	var TaskManager = /*#__PURE__*/function () {
	  function TaskManager(rootNode) {
	    babelHelpers.classCallCheck(this, TaskManager);
	    _classPrivateFieldInitSpec(this, _application, {
	      writable: true,
	      value: void 0
	    });
	    this.rootNode = document.querySelector(rootNode);
	  }
	  babelHelpers.createClass(TaskManager, [{
	    key: "start",
	    value: function start() {
	      var _this = this;
	      var button = main_core.Dom.create('button', {
	        text: 'Открыть компонент',
	        events: {
	          click: function click() {
	            return _this.attachTemplate();
	          }
	        }
	      });
	      main_core.Dom.append(button, this.rootNode);
	    }
	  }, {
	    key: "attachTemplate",
	    value: function attachTemplate() {
	      var context = this;
	      babelHelpers.classPrivateFieldSet(this, _application, ui_vue3.BitrixVue.createApp({
	        name: 'TaskManager',
	        components: {
	          TaskList: TaskList
	        },
	        beforeCreate: function beforeCreate() {
	          this.$bitrix.Application.set(context);
	        },
	        template: '<TaskList/>'
	      }));
	      babelHelpers.classPrivateFieldGet(this, _application).mount(this.rootNode);
	    }
	  }, {
	    key: "detachTemplate",
	    value: function detachTemplate() {
	      if (babelHelpers.classPrivateFieldGet(this, _application)) {
	        babelHelpers.classPrivateFieldGet(this, _application).unmount();
	      }
	      this.start();
	    }
	  }]);
	  return TaskManager;
	}();

	exports.TaskManager = TaskManager;

}((this.BX = this.BX || {}),BX,BX));
//# sourceMappingURL=application.js.map
