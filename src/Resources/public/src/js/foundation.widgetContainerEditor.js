/**
 * Foundation WidgetContainerEditor plugin by Samuel Moncarey
 * Licensed under MIT Open Source
 */

!function ($) {

    /**
     * WidgetContainerEditor module.
     * @module foundation.widgetContainerEditor
     */

    class WidgetContainerEditor {

        constructor(element, options) {

            this.$element = element;
            this.options = $.extend({}, WidgetContainerEditor.defaults, element.data(), options);

            this._init();

            Foundation.registerPlugin(this, 'WidgetContainerEditor');
        }

        _init() {
            this.widgetId = this.options.widgetId;
            this.containerName = this.options.containerName;
            this.$toolbar = this.$element.find('> [data-container-toolbar]');

            if ($('#content-widgets-reveal').length > 0) {
                this.$contentWidgetsReveal = $('#content-widgets-reveal');
            } else {
                this.$contentWidgetsReveal = $('<div id="content-widgets-reveal" class="reveal content-widgets-reveal" data-reveal>');
                $('body').append(this.$contentWidgetsReveal);
                this.$contentWidgetsReveal.foundation();
            }
            if (this._isMasterContainer()) {
                this.$widgets = this.$element.find('.widget');
                this.$childContainers = this.$element.find('.widgetcontainer');
                this.widgetsMovable = false;
                this.$dragged = null;
                this.$placeholder = $('<div class="widget widget-placeholder">');
            }

            this._events();
        }

        _isMasterContainer() {
            return this.options.containerType === 'mastercontainer';
        }

        _events() {
            const _this = this;

            this.$toolbar.on('click', '[data-new-widget]', this._getWidgetTypes.bind(this));
            if (this._isMasterContainer()) {
                this.$element.on('click', '[data-toggle-toolbars]', function (e) {
                    e.preventDefault();
                    _this.$element.toggleClass('preview');
                });

                if (this.options.isStandalone) {
                    this.$toolbar.on('click', '[data-save-container]', this._saveContainer.bind(this));
                    this.$toolbar.on('click', '[data-container-versions]', this._getContainerVersions.bind(this));
                }

                this.$widgets.on('mouseenter', '> [data-widget-toolbar] [data-move-widget]', function () {
                    _this.widgetsMovable = true;
                });
                this.$widgets.on('mouseleave', '> [data-widget-toolbar] [data-move-widget]', function () {
                    _this.widgetsMovable = false;
                });
                this.$widgets.on('dragstart', function (e) {
                    if (!_this.widgetsMovable) return false;
                    e.stopPropagation();
                    _this.$dragged = $(e.currentTarget);
                    const dt = e.originalEvent.dataTransfer;
                    dt.effectAllowed = 'move';
                });
                this.$widgets.add(this.$childContainers).on('dragenter', function (e) {
                    e.stopPropagation();
                    const $target = $(e.currentTarget);
                    if (!$target.is(_this.$dragged) && !$.contains(_this.$dragged, $target)) {
                        if ($target.is('.widgetcontainer')) {
                            $target.find('> [data-container-toolbar]').after(_this.$dragged);
                            return true;
                        }
                        if ($target.is('.widget')) {
                            if (_this.$dragged.index() < $target.index()) {
                                $target.after(_this.$dragged);
                                return true;
                            }
                            if (_this.$dragged.index() > $target.index()) {
                                $target.before(_this.$dragged);
                                return true;
                            }
                        }
                    }
                });
                this.$widgets.on('dragend', function (e) {
                    e.stopPropagation();
                    const $widget = $(e.currentTarget),
                        $container = $widget.parents('.widgetcontainer');
                    _this._ajaxGet('/widget/' + $widget.data('widgetId') + '/move-to/' + $container.data('containerName') + '/position/' + ($widget.index() - 1));
                });
            }
        }

        _revealEvents() {
            this.$contentWidgetsReveal.off('click', 'a[data-select-type]').on('click', 'a[data-select-type]', this._createWidget.bind(this));

            this.$contentWidgetsReveal.off('submit', 'form').on('submit', 'form', this._submitCreatedWidget.bind(this));
        }

        _getWidgetTypes(e) {
            e.preventDefault();
            this._ajaxGet('/widget/types');
        }

        _createWidget(e) {
            e.preventDefault();
            const widget_type = $(e.currentTarget).data('selectType');
            this._ajaxGet('/container/' + this.containerName + '/add-widget/' + widget_type);
        }

        _submitCreatedWidget(e) {
            e.preventDefault();
            const $form = $(e.currentTarget);
            this._ajaxFormSubmit($form);
        }

        _saveContainer(e) {
            e.preventDefault();
            this._ajaxGet('/container/' + this.containerName + '/save');
        }

        _getContainerVersions(e) {
            e.preventDefault();
            this._ajaxGet('/container/' + this.containerName + '/versions');
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
            console.log(data.action);
            switch (data.action) {
                case 'open-reveal':
                    this._openReveal(data.html);
                    break;
                case 'display-new-widget':
                    this._displayNewWidget(data.html);
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

        _displayNewWidget(html) {
            this.$contentWidgetsReveal.foundation('close');
            const $widget = $(html);
            this.$element.append($widget);
            $widget.foundation();
        }

        destroy() {
            Foundation.unregisterPlugin(this);
        }

        static get defaults() {
            return {
                containerId: '',
                containerName: '',
                containerType: '',
                isStandalone: false,
                rootPath: ''
            };
        }
    }

    // Window exports
    Foundation.plugin(WidgetContainerEditor, 'WidgetContainerEditor');

} (jQuery);
