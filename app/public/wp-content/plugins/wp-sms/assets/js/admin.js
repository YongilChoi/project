﻿jQuery(document).ready(function ($) {

    WpSMSGeneral.init();
    WpSmsNotifications.init();
    WpSmsBuddyPress.init();
    WpSmsWoocommerce.init();
    WpSmsJobManager.init();
    WpSmsUltimateMember.init();

    if (jQuery('#subscribe-meta-box').length) {
        WpSmsMetaBox.init();
    }

    if (jQuery('#wpcf7-contact-form-editor').length && jQuery('#wpsms-tab').length) {
        WpSmsContactForm7.init();
    }

    let WpSmsSelect2 = $('.js-wpsms-select2')
    let WpSmsExportForm = $('.js-wpSmsExportForm')

    function matchCustom(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
            return data;
        }

        // Do not display the item if there is no 'text' property
        if (typeof data.text === 'undefined') {
            return null;
        }

        // `params.term` should be the term that is used for searching
        // `data.text` is the text that is displayed for the data object
        if (data.text.indexOf(params.term) > -1 || data.element.getAttribute('value') !== null && data.element.getAttribute('value').toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
            var modifiedData = $.extend({}, data, true);
            modifiedData.text += ' (matched)';

            // You can return modified objects from here
            // This includes matching the `children` how you want in nested data sets
            return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    }

    const WpSmsSelect2Options = {
        placeholder: "Please select",
    };

    if (WpSmsExportForm.length) {
        WpSmsSelect2Options.dropdownParent = WpSmsSelect2.parent()
    }

    // Select2
    WpSmsSelect2.select2(WpSmsSelect2Options);

    // Auto submit the gateways form, after changing value
    $("#wpsms_settings\\[gateway_name\\]").on('change', function () {
        $('input[name="submit"]').click();
    });

    if ($('.repeater').length) {
        $('.repeater').repeater({
            initEmpty: false,
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if (confirm('Are you sure you want to delete this item?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            isFirstItemUndeletable: true
        });
    }
});


/**
 * General
 * @type {{init: WpSMSGeneral.init, alreadyEnabled: ((function(): (boolean|undefined))|*), getFields: WpSMSGeneral.getFields}}
 */
let WpSMSGeneral = {

    getFields: function () {
        this.fields = {
            internatioanlMode: {
                element: jQuery('#wpsms_settings\\[international_mobile\\]'),
            },
            mobileMinimumChar: {
                element: jQuery('#wpsms_settings\\[mobile_terms_minimum\\]'),
            },
            mobileMaximumChar: {
                element: jQuery('#wpsms_settings\\[mobile_terms_maximum\\]'),
            },
            onlyCountries: {
                element: jQuery('#wpsms_settings\\[international_mobile_only_countries\\]'),
            },
            preferredCountries: {
                element: jQuery('#wpsms_settings\\[international_mobile_preferred_countries\\]'),
            }
        }
    },

    hideOrShowFields: function () {
        if (this.fields.internatioanlMode.element.is(':checked')) {
            this.fields.onlyCountries.element.closest('tr').show()
            this.fields.preferredCountries.element.closest('tr').show()
            this.fields.mobileMinimumChar.element.closest('tr').hide()
            this.fields.mobileMaximumChar.element.closest('tr').hide()
        } else {
            this.fields.onlyCountries.element.closest('tr').hide()
            this.fields.preferredCountries.element.closest('tr').hide()
            this.fields.mobileMinimumChar.element.closest('tr').show()
            this.fields.mobileMaximumChar.element.closest('tr').show()
        }
    },

    addEventListener: function () {
        this.fields.internatioanlMode.element.on('change', function () {
            this.hideOrShowFields();
        }.bind(this));
    },

    init: function () {
        this.getFields();
        this.hideOrShowFields();
        this.addEventListener();
    }

}


/**
 * Notifications
 * @type {{init: WpSmsNotifications.init, alreadyEnabled: ((function(): (boolean|undefined))|*), getFields: WpSmsNotifications.getFields}}
 */
let WpSmsNotifications = {

    getFields: function () {
        this.fields = {
            receiverField: {
                element: jQuery('#wpsms_settings\\[notif_publish_new_post_receiver\\]'),
            },
            subscriberField: {
                element: jQuery('#wpsms_settings\\[notif_publish_new_post_default_group\\]'),
            },
            numbersField: {
                element: jQuery('#wpsms_settings\\[notif_publish_new_post_numbers\\]'),
            },
            usersField: {
                element: jQuery('#wpsms_settings\\[notif_publish_new_post_users\\]'),
            }
        }
    },

    hideOrShowFields: function () {
        if (this.fields.receiverField.element.val() == 'subscriber') {
            this.fields.subscriberField.element.closest('tr').show()
            this.fields.numbersField.element.closest('tr').hide()
            this.fields.usersField.element.closest('tr').hide()
        } else if (this.fields.receiverField.element.val() == 'numbers') {
            this.fields.subscriberField.element.closest('tr').hide()
            this.fields.numbersField.element.closest('tr').show()
            this.fields.usersField.element.closest('tr').hide()
        } else if (this.fields.receiverField.element.val() == 'users') {
            this.fields.subscriberField.element.closest('tr').hide()
            this.fields.numbersField.element.closest('tr').hide()
            this.fields.usersField.element.closest('tr').show()
        }
    },

    addEventListener: function () {
        this.fields.receiverField.element.on('change', function () {
            this.hideOrShowFields();
        }.bind(this));
    },

    init: function () {
        this.getFields();
        this.hideOrShowFields();
        this.addEventListener();
    }

}

/**
 * BuddyPress
 * @type {{init: WpSmsBuddyPress.init, alreadyEnabled: ((function(): (boolean|undefined))|*), getFields: WpSmsBuddyPress.getFields}}
 */
let WpSmsBuddyPress = {

    getFields: function () {
        this.fields = {
            mobileNumberField: {
                element: jQuery('#wps_pp_settings\\[bp_mobile_field\\]'),
            },
            fieldSelector: {
                element: jQuery('#wps_pp_settings\\[bp_mobile_field_id\\]'),
            },
            syncFields: {
                element: jQuery('#wps_pp_settings\\[bp_sync_fields\\]'),
            }
        }

    },

    hideOrShowFields: function () {
        if (this.fields.mobileNumberField.element.val() != 'used_current_field') {
            this.fields.fieldSelector.element.closest('tr').hide()
            this.fields.syncFields.element.closest('tr').hide()
        } else {
            this.fields.fieldSelector.element.closest('tr').show()
            this.fields.syncFields.element.closest('tr').show()
        }
    },

    addEventListener: function () {
        this.fields.mobileNumberField.element.on('change', function () {
            this.hideOrShowFields();
        }.bind(this));
    },

    init: function () {
        this.getFields();
        this.hideOrShowFields();
        this.addEventListener();
    }

}

/**
 * Woocommerce
 * @type {{init: WpSmsWoocommerce.init, alreadyEnabled: ((function(): (boolean|undefined))|*), getFields: WpSmsWoocommerce.getFields}}
 */
let WpSmsWoocommerce = {

    getFields: function () {
        this.fields = {
            receiverField: {
                element: jQuery('#wps_pp_settings\\[wc_notify_product_receiver\\]'),
            },
            subscriberField: {
                element: jQuery('#wps_pp_settings\\[wc_notify_product_cat\\]'),
            },
            numbersField: {
                element: jQuery('#wps_pp_settings\\[wc_notify_product_roles\\]'),
            },
            checkoutMobileField: {
                element: jQuery('#wps_pp_settings\\[wc_mobile_field\\]'),
            },
            mobileFieldNecessity: {
                element: jQuery('#wps_pp_settings\\[wc_mobile_field_optional\\]'),
            }
        }
    },

    hideOrShowFields: function () {
        if (this.fields.receiverField.element.val() == 'subscriber') {
            this.fields.subscriberField.element.closest('tr').show()
            this.fields.numbersField.element.closest('tr').hide()
        } else {
            this.fields.subscriberField.element.closest('tr').hide()
            this.fields.numbersField.element.closest('tr').show()
        }
    },

    hideOrShowFields2: function () {
        if (this.fields.checkoutMobileField.element.val() == 'add_new_field') {
            this.fields.mobileFieldNecessity.element.closest('tr').show()
        } else {
            this.fields.mobileFieldNecessity.element.closest('tr').hide()
        }
    },

    addEventListener: function () {
        this.fields.receiverField.element.on('change', function () {
            this.hideOrShowFields();
        }.bind(this));
    },

    addEventListener: function () {
        this.fields.checkoutMobileField.element.on('change', function () {
            this.hideOrShowFields2();
        }.bind(this));
    },

    init: function () {
        this.getFields();
        this.hideOrShowFields();
        this.hideOrShowFields2();
        this.addEventListener();
    }

}

/**
 * Job Manager
 * @type {{init: WpSmsJobManager.init, alreadyEnabled: ((function(): (boolean|undefined))|*), getFields: WpSmsJobManager.getFields}}
 */
let WpSmsJobManager = {

    getFields: function () {
        this.fields = {
            receiverField: {
                element: jQuery('#wps_pp_settings\\[job_notify_receiver\\]'),
            },
            subscriberField: {
                element: jQuery('#wps_pp_settings\\[job_notify_receiver_subscribers\\]'),
            },
            numbersField: {
                element: jQuery('#wps_pp_settings\\[job_notify_receiver_numbers\\]'),
            }
        }
    },

    hideOrShowFields: function () {
        if (this.fields.receiverField.element.val() == 'subscriber') {
            this.fields.subscriberField.element.closest('tr').show()
            this.fields.numbersField.element.closest('tr').hide()
        } else {
            this.fields.subscriberField.element.closest('tr').hide()
            this.fields.numbersField.element.closest('tr').show()
        }
    },

    addEventListener: function () {
        this.fields.receiverField.element.on('change', function () {
            this.hideOrShowFields();
        }.bind(this));
    },

    init: function () {
        this.getFields();
        this.hideOrShowFields();
        this.addEventListener();
    }

}

/**
 * UltimateMember
 * @type {{init: WpSmsUltimateMember.init, alreadyEnabled: ((function(): (boolean|undefined))|*), getFields: WpSmsUltimateMember.getFields, hideOrShowFields: WpSmsUltimateMember.hideOrShowFields, addEventListener: WpSmsUltimateMember.addEventListener}}
 */
let WpSmsUltimateMember = {

    getFields: function () {
        this.fields = {
            mobileNumberField: {
                element: jQuery('#wps_pp_settings\\[um_field\\]'),
                active: false,
            },
            syncOldMembersField: {
                element: jQuery('#wps_pp_settings\\[um_sync_previous_members\\]'),
                active: true,
            },
            fieldSelector: {
                element: jQuery('#wps_pp_settings\\[um_sync_field_name\\]'),
                active: true,
            }
        }

    },

    alreadyEnabled: function () {
        if (this.fields.mobileNumberField.element.is(':checked')) {
            this.fields.syncOldMembersField.active = false;
            this.fields.syncOldMembersField.element.closest('tr').hide()
            return true;
        }
    },

    hideOrShowFields: function () {

        const condition = this.fields.mobileNumberField.element.is(':checked');

        if (condition) {
            for (const field in this.fields) {
                if (this.fields[field].active) this.fields[field].element.closest('tr').show();
            }
        } else {
            for (const field in this.fields) {
                if (this.fields[field].active) this.fields[field].element.closest('tr').hide();
            }
        }
    },

    addEventListener: function () {
        this.fields.mobileNumberField.element.on('change', function () {
            this.hideOrShowFields();
        }.bind(this));
    },

    init: function () {
        this.getFields();
        this.alreadyEnabled();
        this.hideOrShowFields();
        this.addEventListener();
    }

}

/**
 * Contact Form 7
 * @type {{init: WpSmsContactForm7.init, hideOrShowFields: WpSmsContactForm7.hideOrShowFields, setFields: WpSmsContactForm7.setFields, addEventListener: WpSmsContactForm7.addEventListener}}
 */
let WpSmsContactForm7 = {

    /**
     * Initialize Functions
     */
    init: function () {
        this.setFields()
        this.hideOrShowFields()
        this.addEventListener()
    },

    /**
     * Initialize jQuery Selectors
     */
    setFields: function () {
        this.fields = {
            recipient: {
                element: jQuery('#wpcf7-sms-recipient')
            },
            recipient_numbers: {
                element: jQuery('#wp-sms-recipient-numbers')
            },
            recipient_groups: {
                element: jQuery('#wp-sms-recipient-groups')
            },
            message_body: {
                element: jQuery('#wp-sms-cf7-message-body')
            }
        }
    },

    /**
     *  Show or Hide content by changing the Select HTMl tag
     */
    hideOrShowFields: function () {
        if (this.fields.recipient.element.val() === 'number') {
            this.fields.recipient_numbers.element.show()
            this.fields.recipient_groups.element.hide()
            this.fields.message_body.element.show()

        } else {
            this.fields.recipient_numbers.element.hide()
            this.fields.recipient_groups.element.show()
            this.fields.message_body.element.show()

        }
    },

    addEventListener: function () {
        this.fields.recipient.element.on('change', function () {
            this.hideOrShowFields();
        }.bind(this));
    },

}

/**
 * Meta Box
 * @type {{init: WpSmsMetaBox.init, hideOrShowFields: WpSmsMetaBox.hideOrShowFields, setFields: WpSmsMetaBox.setFields, addEventListener: WpSmsMetaBox.addEventListener}}
 */
let WpSmsMetaBox = {

    /**
     * Initialize Functions
     */
    init: function () {
        this.setFields()
        this.hideOrShowFields()
        this.addEventListener()
    },

    /**
     * Initialize jQuery Selectors
     */
    setFields: function () {
        this.fields = {
            recipient: {
                element: jQuery('#wps-send-to'),

                subscriber: {
                    element: jQuery('#wpsms-select-subscriber-group'),
                },

                numbers: {
                    element: jQuery('#wpsms-select-numbers'),
                },

                users: {
                    element: jQuery('#wpsms-select-users'),
                }
            },
            message_body: {
                element: jQuery('#wpsms-custom-text'),
            }
        }
    },

    /**
     *  Show or Hide content by changing the Select HTMl tag
     */
    hideOrShowFields: function () {
        if (this.fields.recipient.element.val() === 'subscriber') {
            this.fields.recipient.subscriber.element.show()
            this.fields.recipient.numbers.element.hide()
            this.fields.recipient.users.element.hide()
            this.fields.message_body.element.show()

        } else if (this.fields.recipient.element.val() === 'numbers') {
            this.fields.recipient.subscriber.element.hide()
            this.fields.recipient.numbers.element.show()
            this.fields.recipient.users.element.hide()
            this.fields.message_body.element.show()

        } else if (this.fields.recipient.element.val() === 'users') {
            this.fields.recipient.subscriber.element.hide()
            this.fields.recipient.numbers.element.hide()
            this.fields.recipient.users.element.show()
            this.fields.message_body.element.show()

        } else {
            this.fields.recipient.subscriber.element.hide()
            this.fields.recipient.numbers.element.hide()
            this.fields.recipient.users.element.hide()
            this.fields.message_body.element.hide()
        }
    },

    addEventListener: function () {
        this.fields.recipient.element.on('change', function () {
            this.hideOrShowFields();
        }.bind(this));
    },

}