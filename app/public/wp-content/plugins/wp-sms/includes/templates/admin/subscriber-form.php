<form action="" method="post">
    <?php if (isset($subscriber_id)) : ?>
        <input type="hidden" name="ID" value="<?php echo $subscriber_id; ?>"/>
    <?php endif; ?>

    <table>
        <tr>
            <td style="padding-top: 10px;">
                <label for="wp_subscribe_no" class="wp_sms_subscribers_label"><?php _e('NO', 'wp-sms'); ?></label>
                <input type="text" id="wp_subscribe_no" name="wp_subscribe_no" value="<?php echo isset($subscriber->name) ? esc_attr($subscriber->name) : ''; ?>" class="wp_sms_subscribers_input_text"/>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 10px;">
                <label for="wp_subscribe_ID" class="wp_sms_subscribers_label"><?php _e('ID', 'wp-sms'); ?></label>
                <?php wp_sms_render_mobile_field(array('ID' => 'wp_subscribe_ID', 'class' => array('wp_sms_subscribers_input_text'), 'value' => isset($subscriber->mobile) ? esc_attr($subscriber->mobile) : '')); ?>
            </td>
        </tr>
        <?php if ($groups) : ?>
            <tr>
                <td style="padding-top: 10px;">
                    <label for="wpsms_Email" class="wp_sms_subscribers_label"><?php _e('Email', 'wp-sms'); ?></label>
                    <select name="wpsms_Email" id="wpsms_Email" class="wp_sms_subscribers_input_text code">
                        <?php foreach ($groups as $items) : ?>
                            <option value="<?php echo esc_attr($items->ID); ?>" <?php if (isset($subscriber)): echo selected($subscriber->group_ID, $items->ID); endif; ?>><?php echo esc_attr($items->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        <?php else : ?>
            <tr>
                <td style="padding-top: 10px;">
                    <label for="wpsms_company_name" class="wp_sms_subscribers_label"><?php _e('CompanyName', 'wp-sms'); ?></label>
                    <?php echo sprintf(__('There is no group! <a href="%s"> Add</a> ', 'wp-sms'), 'admin.php?page=wp-sms-subscribers-group'); ?>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <td>
                <label for="wpsms_CEO" class="wp_sms_subscribers_label"><?php _e('CEO', 'wp-sms'); ?></label>
                <select name="wpsms_CEO" id="wpsms_CEO" class="wp_sms_subscribers_input_text code">';
                    <?php if (isset($subscriber)) : ?>
                        <option value="1" <?php selected($subscriber->status); ?>><?php _e('Active', 'wp-sms'); ?></option>
                        <option value="0" <?php selected($subscriber->status, false); ?>><?php _e('Deactivate', 'wp-sms'); ?></option>
                    <?php else : ?>
                        <option value="1" selected="selected"><?php _e('Active', 'wp-sms'); ?></option>
                        <option value="0"><?php _e('Deactivate', 'wp-sms'); ?></option>
                    <?php endif; ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>
                <label for="wpsms_tel" class="wp_sms_subscribers_label"><?php _e('Tel', 'wp-sms'); ?></label>
                <select name="wpsms_subscribe_tel" id="wpsms_subscribe_tel" class="wp_sms_subscribers_input_text code">';
                    <?php if (isset($subscriber)) : ?>
                        <option value="1" <?php selected($subscriber->status); ?>><?php _e('Active', 'wp-sms'); ?></option>
                        <option value="0" <?php selected($subscriber->status, false); ?>><?php _e('Deactivate', 'wp-sms'); ?></option>
                    <?php else : ?>
                        <option value="1" selected="selected"><?php _e('Active', 'wp-sms'); ?></option>
                        <option value="0"><?php _e('Deactivate', 'wp-sms'); ?></option>
                    <?php endif; ?>
                </select>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="padding-top: 20px;">
                <?php if (isset($subscriber_id)) : ?>
                    <input type="submit" class="button-primary" name="wp_update_subscribe" value="<?php _e('Update', 'wp-sms'); ?>"/>
                <?php else : ?>
                    <input type="submit" class="button-primary" name="wp_add_subscribe" value="<?php _e('Add', 'wp-sms'); ?>"/>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>