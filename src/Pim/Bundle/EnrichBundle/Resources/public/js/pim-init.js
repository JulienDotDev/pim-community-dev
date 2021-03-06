define(
    ['jquery', 'backbone', 'oro/translator', 'oro/mediator', 'oro/navigation', 'oro/messenger', 'pim/dialog',
     'pim/saveformstate', 'pim/asynctab', 'pim/ui', 'oro/loading-mask'],
    function ($, Backbone, __, mediator, Navigation, messenger, Dialog, saveformstate, loadTab, UI, LoadingMask) {
        'use strict';
        var initialized = false;
        return function () {
            if (initialized) {
                return;
            }
            initialized = true;
            var pageInit = function ($target) {
                if (!$target) {
                    $target = $('body');
                    $target.find('form.form-horizontal, [data-saveformstate]').each(function () {
                        saveformstate($(this).attr('id'), loadTab);
                    });
                }
                // Place code that we need to run on every page load here

                $target.find('.remove-attribute').each(function () {
                    var target = $(this).parent().find('.icons-container');
                    if (target.length) {
                        $(this).appendTo(target).attr('tabIndex', -1);
                    }
                });

                // Toogle accordion icon
                $target.find('.accordion').on('show hide', function (e) {
                    $(e.target).siblings('.accordion-heading')
                        .find('.accordion-toggle i')
                        .toggleClass('icon-chevron-down icon-chevron-right');
                });

                var $localizableIcon = $('<i>', {
                    'class': 'icon-globe',
                    'attr': {
                        'data-original-title': __('Localized value'),
                        'data-toggle': 'tooltip',
                        'data-placement': 'right'
                    }
                });
                $target.find('.attribute-field.localizable').each(function () {
                    var $iconsContainers = $(this).find('div.controls').find('.icons-container');
                    if (!$iconsContainers.find('i.icon-globe').length) {
                        $iconsContainers.prepend($localizableIcon.clone());
                    }
                });

                UI($target);

                $target.find('a[data-form-toggle]').on('click', function () {
                    $('#' + $(this).attr('data-form-toggle')).show();
                    $(this).hide();
                });

                $target.find('a[data-toggle="tab"]').on('show.bs.tab', function () {
                    loadTab(this);
                });
            };

            $(function () {
                $(document).on('tab.loaded', 'form.form-horizontal, [data-saveformstate]', function (e, tab) {
                    pageInit($(tab));
                });

                var secret = '38384040373937396665';
                var input = '';
                var timer;
                $(document).keyup(function (e) {
                    input += e.which;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        input = '';
                    }, 500);
                    if (input === secret) {
                        $(document.body).addClass('konami');
                    }
                });

                // DELETE request for delete buttons
                $(document).on('click', '[data-dialog]', function () {
                    var $el      = $(this);
                    var message  = $el.data('message');
                    var title    = $el.data('title');
                    var doAction = function () {
                        var loadingMask = new LoadingMask();
                        loadingMask.render().$el.appendTo($(document.body)).css(
                            {
                                'position': 'absolute',
                                'top': '0px',
                                'left': '0px',
                                'width': '100%',
                                'height': '100%'
                            }
                        );
                        loadingMask.show();

                        $.ajax({
                            url: $el.attr('data-url'),
                            type: 'POST',
                            headers: { accept: 'application/json' },
                            data: { _method: $el.data('method') },
                            success: function () {
                                loadingMask.hide().$el.remove();
                                var navigation = Navigation.getInstance();
                                var targetUrl = '#url=' + $el.attr('data-redirect-url');
                                // If already on the desired page, make sure it is refreshed
                                Backbone.history.fragment = new Date().getTime();
                                navigation.navigate(targetUrl, { trigger: true });
                                navigation.addFlashMessage('success', $el.attr('data-success-message'));
                            },
                            error: function (xhr) {
                                loadingMask.hide().$el.remove();
                                messenger.notificationFlashMessage(
                                    'error',
                                    (xhr.responseJSON && xhr.responseJSON.message) ?
                                        xhr.responseJSON.message :
                                        $el.attr('data-error-message'));
                            }
                        });
                    };
                    $el.off('click');
                    if ($el.data('dialog') === 'confirm') {
                        Dialog.confirm(message, title, doAction);
                    } else {
                        Dialog.alert(message, title);
                    }

                    return false;
                });

                pageInit();
            });
            mediator.bind('hash_navigation_request:complete pim:reinit', function () {
                pageInit();
            });
        };
    }
);
