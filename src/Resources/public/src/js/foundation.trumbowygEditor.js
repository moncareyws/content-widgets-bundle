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

    class TrumbowygEditor {

        constructor(element, options) {
            this.$element = element;

            this.options = $.extend({}, TrumbowygEditor.defaults, this.$element.data(), options);

            this._init();

            Foundation.registerPlugin(this, 'TrumbowygEditor');
        }

        _init() {
            this.$element.trumbowyg(this.options);
        }

        /**
         * Destroys the select.
         * @function
         */
        destroy() {
            this.$element.trumbowyg('destroy');
            Foundation.unregisterPlugin(this);
        }

        static get defaults() {
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
    }

    // Window exports
    Foundation.plugin(TrumbowygEditor, 'TrumbowygEditor');

} (jQuery);
