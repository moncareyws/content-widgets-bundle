/**
 * Foundation WidgetEditor plugin by Samuel Moncarey
 * Licensed under MIT Open Source
 */

!function ($) {

    /**
     * WidgetContainerEditor module.
     * @module foundation.widgetEditor
     */

    class WidgetEditor {

        constructor(element, options) {

            this.$element = element;
            this.options = $.extend({}, WidgetEditor.defaults, element.data(), options);

            this._init();

            Foundation.registerPlugin(this, 'WidgetEditor');
        }

        _init() {
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

        _events() {
            this.$toolbar.on('click', '[data-delete-widget]', this._deleteWidget.bind(this));
            this.$toolbar.on('click', '[data-edit-widget]', this._editWidget.bind(this));
            this.$toolbar.on('click', '[data-toggle-hidden]', this._toggleHidden.bind(this));
        }

        _revealEvents() {
            this.$contentWidgetsReveal.off('submit', 'form').on('submit', 'form', this._submitWidgetForm.bind(this));
        }

        _deleteWidget(e) {
            e.preventDefault();
            console.log('_deleteWidget');
            this._ajaxGet('/widget/' + this.widgetId + '/delete');
        }

        _editWidget(e) {
            e.preventDefault();
            console.log('_editWidget');
            this._ajaxGet('/widget/' + this.widgetId + '/edit');
        }

        _toggleHidden(e) {
            e.preventDefault();
            console.log('_toggleHidden');
            this._ajaxGet('/widget/' + this.widgetId + '/toggle-hidden');
        }

        _submitWidgetForm(e) {
            e.preventDefault();
            const $form = $(e.currentTarget);
            this._ajaxFormSubmit($form);
        }

        _ajaxGet(url, data) {
            const _this = this;

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

        _ajaxFormSubmit($form) {
            const _this = this;

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

        _ajaxCallback(data) {
            switch (data.action) {
                case 'open-reveal':
                    this._openReveal(data.html);
                    break;
                case 'update-widget':
                    this.$contentWidgetsReveal.foundation('close');
                    this.$element.find('[data-tooltip]').foundation('destroy');
                    const $html = $(data.html);
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

        _openReveal(html) {
            this.$contentWidgetsReveal.html(html);
            this.$contentWidgetsReveal.find('> *').foundation();
            this.$contentWidgetsReveal.foundation('open');
            this._revealEvents();
        }

        /**
         * Destroys the instance of WidgetEditor on the element.
         * @function
         */
        destroy() {
            Foundation.unregisterPlugin(this);
        }

        static get defaults() {
            return {};
        }

    }

    // Window exports
    Foundation.plugin(WidgetEditor, 'WidgetEditor');

} (jQuery);
