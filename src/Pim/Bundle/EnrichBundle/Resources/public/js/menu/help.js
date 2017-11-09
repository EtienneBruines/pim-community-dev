/**
 * Extension for displaying help link with version numbers
 *
 * @author    Tamara Robichet <julien@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
define(
    [
        'underscore',
        'oro/translator',
        'pim/form',
        'pim/data-collector',
        'pim/template/menu/tab'
    ],
    function(
        _,
        __,
        BaseForm,
        DataCollector,
        template
    ) {
        return BaseForm.extend({
            analyticsUrl: 'pim_analytics_data_collect',
            helpUrl: 'https://help.akeneo.com/?utm_source=akeneo-app&utm_medium=interrogation-icon&utm_campaign=',
            className: 'AknHeader-menuItemContainer',
            template: _.template(template),

            /**
             * {@inheritdoc}
             */
            render: function() {
                this.getUrl().then(url => {
                    this.$el.empty().append(this.template({
                        title: __('pim_menu.tab.help'),
                        url,
                        active: false,
                        iconModifier: 'iconHelp'
                    }));
                });

                return BaseForm.prototype.render.apply(this, arguments);
            },

            /**
             * {@inheritdoc}
             */
            getUrl() {
                return DataCollector.collect(this.analyticsUrl).then((data) => {
                    const { pim_version, pim_edition } = data;

                    return `${this.helpUrl}${pim_edition}${pim_version}`;
                });
            }
        });
    });
