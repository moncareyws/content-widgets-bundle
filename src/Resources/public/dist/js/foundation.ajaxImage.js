'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Foundation AjaxImage plugin by Samuel Moncarey
 * Licensed under MIT Open Source
 */

!function ($, mu) {
    "use strict";

    /**
     * TrumbowygEditor module.
     * @module foundation.ajaxImage
     */

    var AjaxImage = function () {
        function AjaxImage(element, options) {
            _classCallCheck(this, AjaxImage);

            this.$element = element;

            this.options = $.extend({}, AjaxImage.defaults, this.$element.data(), options);

            this._init();

            Foundation.registerPlugin(this, 'AjaxImage');
        }

        _createClass(AjaxImage, [{
            key: '_init',
            value: function _init() {
                this.$fileInput = $(mu.render('<input type="file" accept="{{ filemime }}">', { filemime: this.options.accept }));
                this.$preview = $(mu.render('<a>' + '<img src="{{ defaultImage }}">' + '<div class="loading-spinner"></div>' + '</a>', { defaultImage: this.options.defaultImage }));
                this.$input.wrap('<div class="ajax-image-container"></div>').after(this.$fileInput).after(this.$preview);

                this.$preview.on('click', this._trigger.bind(this));
                this.$fileInput.on('change', this.upload.bind(this));
                this.$loader = this.$preview.find('.loading-spinner');

                this.$loader.spin(this.options.spinner);
            }
        }, {
            key: '_trigger',
            value: function _trigger(e) {
                e.preventDefault();
                this.$fileInput.trigger('click');
            }
        }, {
            key: 'upload',
            value: function upload() {
                var data = new FormData();
                data.append('file', this.$fileInput[0].files[0]);

                Foundation.Motion.animateIn(this.$loader, 'fade-in');

                $.ajax({
                    type: 'post',
                    url: this.options.uploadUrl,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    data: data,
                    complete: this.onUploadComplete.bind(this)
                });
            }
        }, {
            key: 'onUploadComplete',
            value: function onUploadComplete(data) {
                var file = data.responseJSON.file;
                this.$input.val(file);
                this.$preview.find('img').prop('src', file);

                Foundation.Motion.animateOut(this.$loader, 'fade-out');
            }
        }], [{
            key: 'defaults',
            get: function get() {
                return {
                    accept: 'image/*',
                    defaultImage: '',
                    uploadUrl: '',
                    spinner: {
                        lines: 9,
                        length: 12,
                        width: 8,
                        radius: 16,
                        scale: 1,
                        corners: 1,
                        color: '#fff',
                        opacity: 0.1,
                        rotate: 0,
                        direction: 1,
                        speed: 1,
                        trail: 60,
                        fps: 20,
                        zIndex: 2e9,
                        className: 'spinner',
                        top: '50%',
                        left: '50%',
                        shadow: true,
                        hwaccel: true,
                        position: 'absolute'
                    }
                };
            }
        }]);

        return AjaxImage;
    }();

    // Window exports


    Foundation.plugin(AjaxImage, 'AjaxImage');
}(jQuery, Mustache);