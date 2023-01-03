<div class="notice notice-warning wpsms-newsletter__notice">
    <form action="https://static.mailerlite.com/webforms/submit/k7h5a2" data-code="k7h5a2" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
        <?php _e('Get the latest updates and special deals by signing up for WP SMS newsletters', 'wp-sms'); ?>

        <div class="field-group">
            <input type="email" value="<?php bloginfo('admin_email'); ?>" name="fields[email]" class="required email" id="mce-EMAIL">
            <input type="hidden" name="ml-submit" value="1">
            <input type="hidden" name="anticsrf" value="true">
            <input type="submit" value="<?php _e('Subscribe', 'wp-sms'); ?>" name="subscribe" id="mc-embedded-subscribe" class="button button-primary">
        </div>

        <a href="<?php echo admin_url('admin.php?page=wp-sms-settings&action=wpsms-hide-newsletter'); ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>
    </form>
</div>