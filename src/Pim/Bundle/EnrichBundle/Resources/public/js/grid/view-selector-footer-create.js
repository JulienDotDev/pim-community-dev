'use strict';

/**
 * Footer extension for the Datagrid View Selector.
 *
 * Contains a "create" button to allow the user to create a view from the current
 * state of the grid (regarding filters and columns).
 *
 * @author    Willy MESNAGE <willy.mesnage@akeneo.com>
 * @copyright 2016 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
define(
    [
        'jquery',
        'underscore',
        'oro/translator',
        'pim/form',
        'text!pim/template/grid/view-selector/footer/create'
    ],
    function (
        $,
        _,
        __,
        BaseForm,
        template
    ) {
        return BaseForm.extend({
            template: _.template(template),

            /**
             * {@inheritdoc}
             */
            render: function () {
                var extensionsCount = 0;

                _.each(this.extensions, function (extension) {
                    if ('action-list' === extension.targetZone) {
                        extensionsCount++;
                    }
                });
                this.$el.html(this.template({
                    isMultiple: 1 < extensionsCount,
                    createButtonTitle: 'Create' // TODO: translation
                }));

                if (1 < extensionsCount) {
                    this.$('.dropdown-toggle').dropdown();
                }

                this.renderExtensions(extensionsCount);

                return this;
            },

            /**
             * {@inheritdoc}
             */
            renderExtensions: function (extensionsCount) {
                this.initializeDropZones();

                if (1 === extensionsCount) {
                    _.each(this.extensions, function (extension) {
                        this.renderExtension(extension);
                    }.bind(this));
                } else if (1 < extensionsCount) {
                    _.each(this.extensions, function (extension) {
                        this.renderExtensionAsList(extension);
                    }.bind(this));
                }

                return this;
            },

            /**
             * Render a single extension as list
             *
             * @param {Object} extension
             */
            renderExtensionAsList: function (extension) {
                var rendering = $('<li>').append(extension.el);

                this.getZone(extension.targetZone).appendChild(rendering[0]);

                extension.render();
            }
        });
    }
);
