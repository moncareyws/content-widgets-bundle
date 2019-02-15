'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Foundation WidgetContainerEditor plugin by Samuel Moncarey
 * Licensed under MIT Open Source
 */

!function ($) {

    /**
     * WidgetContainerEditor module.
     * @module foundation.widgetContainerEditor
     */

    var WidgetContainerEditor = function () {
        function WidgetContainerEditor(element, options) {
            _classCallCheck(this, WidgetContainerEditor);

            this.$element = element;
            this.options = $.extend({}, WidgetContainerEditor.defaults, element.data(), options);

            this._init();

            Foundation.registerPlugin(this, 'WidgetContainerEditor');
        }

        _createClass(WidgetContainerEditor, [{
            key: '_init',
            value: function _init() {
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
        }, {
            key: '_isMasterContainer',
            value: function _isMasterContainer() {
                return this.options.containerType === 'mastercontainer';
            }
        }, {
            key: '_events',
            value: function _events() {
                var _this = this;

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
                        var dt = e.originalEvent.dataTransfer;
                        dt.effectAllowed = 'move';
                    });
                    this.$widgets.add(this.$childContainers).on('dragenter', function (e) {
                        e.stopPropagation();
                        var $target = $(e.currentTarget);
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
                        var $widget = $(e.currentTarget),
                            $container = $widget.parents('.widgetcontainer');
                        _this._ajaxGet('/widget/' + $widget.data('widgetId') + '/move-to/' + $container.data('containerName') + '/position/' + ($widget.index() - 1));
                    });
                }
            }
        }, {
            key: '_revealEvents',
            value: function _revealEvents() {
                this.$contentWidgetsReveal.off('click', 'a[data-select-type]').on('click', 'a[data-select-type]', this._createWidget.bind(this));

                this.$contentWidgetsReveal.off('submit', 'form').on('submit', 'form', this._submitCreatedWidget.bind(this));
            }
        }, {
            key: '_getWidgetTypes',
            value: function _getWidgetTypes(e) {
                e.preventDefault();
                this._ajaxGet('/widget/types');
            }
        }, {
            key: '_createWidget',
            value: function _createWidget(e) {
                e.preventDefault();
                var widget_type = $(e.currentTarget).data('selectType');
                this._ajaxGet('/container/' + this.containerName + '/add-widget/' + widget_type);
            }
        }, {
            key: '_submitCreatedWidget',
            value: function _submitCreatedWidget(e) {
                e.preventDefault();
                var $form = $(e.currentTarget);
                this._ajaxFormSubmit($form);
            }
        }, {
            key: '_saveContainer',
            value: function _saveContainer(e) {
                e.preventDefault();
                this._ajaxGet('/container/' + this.containerName + '/save');
            }
        }, {
            key: '_getContainerVersions',
            value: function _getContainerVersions(e) {
                e.preventDefault();
                this._ajaxGet('/container/' + this.containerName + '/versions');
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
        }, {
            key: '_openReveal',
            value: function _openReveal(html) {
                this.$contentWidgetsReveal.html(html);
                this.$contentWidgetsReveal.find('> *').foundation();
                this.$contentWidgetsReveal.foundation('open');
                this._revealEvents();
            }
        }, {
            key: '_displayNewWidget',
            value: function _displayNewWidget(html) {
                this.$contentWidgetsReveal.foundation('close');
                var $widget = $(html);
                this.$element.append($widget);
                $widget.foundation();
            }
        }, {
            key: 'destroy',
            value: function destroy() {
                Foundation.unregisterPlugin(this);
            }
        }], [{
            key: 'defaults',
            get: function get() {
                return {
                    containerId: '',
                    containerName: '',
                    containerType: '',
                    isStandalone: false,
                    rootPath: ''
                };
            }
        }]);

        return WidgetContainerEditor;
    }();

    // Window exports


    Foundation.plugin(WidgetContainerEditor, 'WidgetContainerEditor');
}(jQuery);