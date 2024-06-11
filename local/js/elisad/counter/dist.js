/* eslint-disable */
(function (exports,ui_vue3) {
    'use strict';

    function _classPrivateFieldInitSpec(obj, privateMap, value) { _checkPrivateRedeclaration(obj, privateMap); privateMap.set(obj, value); }
    function _checkPrivateRedeclaration(obj, privateCollection) { if (privateCollection.has(obj)) { throw new TypeError("Cannot initialize the same private elements twice on an object"); } }
    var _application = /*#__PURE__*/new WeakMap();
    var Counter = /*#__PURE__*/function () {
      function Counter(rootNode) {
        babelHelpers.classCallCheck(this, Counter);
        _classPrivateFieldInitSpec(this, _application, {
          writable: true,
          value: void 0
        });
        this.rootNode = document.querySelector(rootNode);
      }
      babelHelpers.createClass(Counter, [{
        key: "start",
        value: function start() {
          var context = this;
          babelHelpers.classPrivateFieldSet(this, _application, ui_vue3.BitrixVue.createApp({
            name: 'Counter',
            data: function data() {
              return {
                counter: 0
              };
            },
            beforeCreate: function beforeCreate() {
              this.$bitrix.Application.set(context);
            },
            mounted: function mounted() {
              var _this = this;
              setInterval(function () {
                _this.counter++;
              }, 1000);
            },
            template: '<div>Counter: {{ counter}}</div>'
          }));
          babelHelpers.classPrivateFieldGet(this, _application).mount(this.rootNode);
        }
      }]);
      return Counter;
    }();

    exports.Counter = Counter;

}((this.BX = this.BX || {}),BX));
//# sourceMappingURL=dist.js.map
