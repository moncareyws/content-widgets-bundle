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

    class AjaxImage {

        constructor(element, options) {
            this.$element = element;

            this.options = $.extend({}, AjaxImage.defaults, this.$element.data(), options);

            this._init();

            Foundation.registerPlugin(this, 'AjaxImage');
        }

        _init() {
            this.$fileInput = $(mu.render('<input type="file" accept="{{ filemime }}">', { filemime: this.options.accept }));
            this.$preview = $(mu.render('<a>' + '<img src="{{ defaultImage }}">' + '<div class="loading-spinner"></div>' + '</a>', { defaultImage: this.options.defaultImage }));
            this.$input.wrap('<div class="ajax-image-container"></div>').after(this.$fileInput).after(this.$preview);

            this.$preview.on('click', this._trigger.bind(this));
            this.$fileInput.on('change', this.upload.bind(this));
            this.$loader = this.$preview.find('.loading-spinner');

            this.$loader.spin(this.options.spinner);
        }

        _trigger(e) {
            e.preventDefault();
            this.$fileInput.trigger('click');
        }

        upload() {
            const data = new FormData();
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

        onUploadComplete(data) {
            const file = data.responseJSON.file;
            this.$input.val(file);
            this.$preview.find('img').prop('src', file);

            Foundation.Motion.animateOut(this.$loader, 'fade-out');
        }

        static get defaults() {
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
    }

    // Window exports
    Foundation.plugin(AjaxImage, 'AjaxImage');

} (jQuery, Mustache);
