'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Foundation TrumbowygEditor plugin by Samuel Moncarey
 * Licensed under MIT Open Source
 */

!function ($) {
    "use strict";

    /**
     * TrumbowygEditor module.
     * @module foundation.trumbowygEditor
     */

    var TrumbowygEditor = function () {
        function TrumbowygEditor(element, options) {
            _classCallCheck(this, TrumbowygEditor);

            this.$element = element;

            this.options = $.extend({}, TrumbowygEditor.defaults, this.$element.data(), options);

            this._init();

            Foundation.registerPlugin(this, 'TrumbowygEditor');
        }

        _createClass(TrumbowygEditor, [{
            key: '_init',
            value: function _init() {
                this.$element.trumbowyg(this.options);
            }

            /**
             * Destroys the select.
             * @function
             */

        }, {
            key: 'destroy',
            value: function destroy() {
                this.$element.trumbowyg('destroy');
                Foundation.unregisterPlugin(this);
            }
        }], [{
            key: 'defaults',
            get: function get() {
                return {
                    lang: 'en',

                    fixedBtnPane: false,
                    fixedFullWidth: false,
                    autogrow: false,

                    prefix: 'trumbowyg-',

                    semantic: true,
                    resetCss: false,
                    removeformatPasted: false,
                    tagsToRemove: [],

                    btns: [['undo', 'redo'], ['formatting'], ['strong', 'em', 'del'],
                    //['superscript', 'subscript'],
                    ['link'], ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'], ['unorderedList', 'orderedList'], ['removeformat', 'viewHTML']],
                    // For custom button definitions
                    btnsDef: {},

                    inlineElementsSelector: 'a,abbr,acronym,b,caption,cite,code,col,dfn,dir,dt,dd,em,font,hr,i,kbd,li,q,span,strikeout,strong,sub,sup,u',

                    pasteHandlers: [],

                    plugins: {}
                };
            }
        }]);

        return TrumbowygEditor;
    }();

    // Window exports


    Foundation.plugin(TrumbowygEditor, 'TrumbowygEditor');
}(jQuery);