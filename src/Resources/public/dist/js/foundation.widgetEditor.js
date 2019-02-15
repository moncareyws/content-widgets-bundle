'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Foundation WidgetEditor plugin by Samuel Moncarey
 * Licensed under MIT Open Source
 */

!function ($) {

    /**
     * WidgetContainerEditor module.
     * @module foundation.widgetEditor
     */

    var WidgetEditor = function () {
        function WidgetEditor(element, option) {
            _classCallCheck(this, WidgetEditor);

            this.$element = element;
            this.options = $.extend({}, WidgetEditor.defaults, element.data(), options);

            this._init();

            Foundation.registerPlugin(this, 'WidgetEditor');
        }

        _createClass(WidgetEditor, [{
            key: '_init',
            value: function _init() {
                this.widgetId = this.options.widgetId;
                this.$toolbar = this.$element.find('> [data-widget-toolbar]');
                this.$container = this.$element.parents('.widgetcontainer');

                if ($('#content-widgets-reveal').length > 0) {
                    this.$contentWidgetsReveal = $('#content-widgets-reveal');
                } else {
                    this.$contentWidgetsReveal = $('<div id="content-widgets-reveal" class="reveal content-widgets-reveal" data-reveal>');
                    $('body').append(this.$contentWidgetsReveal);
                    this.$contentWidgetsReveal.foundation();
                }

                if (this.options.layoutWidget) {
                    this.$row = this.$element.find('> .row');
                    this.$columns = this.$row.find('> .column, > .columns');

                    var eqId = Foundation.GetYoDigits(8, 'widget');
                    this.$row.attr('data-equalizer', eqId);
                    this.$columns.attr('data-equalizer-watch', eqId);

                    this.$eq = new Foundation.Equalizer(this.$row, { equalizeByRow: true });
                }

                this._events();
            }
        }, {
            key: '_events',
            value: function _events() {
                this.$toolbar.on('click', '[data-delete-widget]', this._deleteWidget.bind(this));
                this.$toolbar.on('click', '[data-edit-widget]', this._editWidget.bind(this));
                this.$toolbar.on('click', '[data-toggle-hidden]', this._toggleHidden.bind(this));
            }
        }, {
            key: '_revealEvents',
            value: function _revealEvents() {
                this.$contentWidgetsReveal.off('submit', 'form').on('submit', 'form', this._submitWidgetForm.bind(this));
            }
        }, {
            key: '_deleteWidget',
            value: function _deleteWidget(e) {
                e.preventDefault();
                this._ajaxGet('/widget/' + this.widgetId + '/delete');
            }
        }, {
            key: '_editWidget',
            value: function _editWidget(e) {
                e.preventDefault();
                this._ajaxGet('/widget/' + this.widgetId + '/edit');
            }
        }, {
            key: '_toggleHidden',
            value: function _toggleHidden(e) {
                e.preventDefault();
                this._ajaxGet('/widget/' + this.widgetId + '/toggle-hidden');
            }
        }, {
            key: '_submitWidgetForm',
            value: function _submitWidgetForm(e) {
                e.preventDefault();
                var $form = $(e.currentTarget);
                this._ajaxFormSubmit($form);
            }
        }, {
            key: '_ajaxGet',
            value: function _ajaxGet(url, data) {
                var _this = this;

                if (data === undefined) data = {};
                $.ajax({
                    type: 'get',
                    url: '' + this.options.rootPath + url,
                    dataType: 'json',
                    data: data,
                    success: function success(data) {
                        _this._ajaxCallback(data);
                    }
                });
            }
        }, {
            key: '_ajaxFormSubmit',
            value: function _ajaxFormSubmit($form) {
                var _this = this;

                console.log($form.serializeArray());
                $.ajax({
                    type: 'post',
                    url: $form.attr('action'),
                    dataType: 'json',
                    data: $form.serializeArray(),
                    success: function success(data) {
                        console.log(data);
                        _this._ajaxCallback(data);
                    }
                });
            }
        }, {
            key: '_ajaxCallback',
            value: function _ajaxCallback(data) {
                switch (data.action) {
                    case 'open-reveal':
                        this._openReveal(data.html);
                        break;
                    case 'update-widget':
                        this.$contentWidgetsReveal.foundation('close');
                        this.$element.find('[data-tooltip]').foundation('destroy');
                        var $html = $(data.html);
                        this.$element.replaceWith($html);
                        $html.foundation();
                        break;
                    case 'remove-widget':
                        this.$contentWidgetsReveal.foundation('close');
                        this.$element.find('[data-tooltip]').foundation('destroy');
                        this.$element.remove();
                        break;
                    case 'close-reveal':
                    default:
                        this.$contentWidgetsReveal.foundation('close');
                }

                if (data.messages.length > 0) {}
            }
        }, {
            key: '_openReveal',
            value: function _openReveal(html) {
                this.$contentWidgetsReveal.html(html);
                this.$contentWidgetsReveal.find('> *').foundation();
                this.$contentWidgetsReveal.foundation('open');
                this._revealEvents();
            }

            /**
             * Destroys the instance of WidgetEditor on the element.
             * @function
             */

        }, {
            key: 'destroy',
            value: function destroy() {
                Foundation.unregisterPlugin(this);
            }
        }], [{
            key: 'defaults',
            get: function get() {
                return {};
            }
        }]);

        return WidgetEditor;
    }();

    // Window exports


    Foundation.plugin(WidgetEditor, 'WidgetEditor');
}(jQuery);