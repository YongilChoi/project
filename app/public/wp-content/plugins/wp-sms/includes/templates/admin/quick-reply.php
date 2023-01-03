<?php
namespace WP_SMS;

$groups = Newsletter::getGroups();
?> 



<div id="wpsms-quick-reply" style="display: none">
    <div class="wpsms-sendsms__overlay">
        <svg class="wpsms-sendsms__overlay__spinner" xmlns="http://www.w3.org/2000/svg" style="margin:auto;background:0 0" width="200" height="200" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" display="block">
            <circle cx="50" cy="50" fill="none" stroke="#c6c6c6" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
            </circle>
        </svg>
    </div>

    <div class="wpsms-wrap wpsms-quick-reply-popup">
        <div class="wp-sms-popup-messages"></div>
    </div>
        

<!-- 여기서 문자 전송한 전체 구독자 테이블을 팝업으로 보여준다. -->
<form action="" method="post">
    <?php if (isset($subscriber_id)) : ?>
        <input type="hidden" name="ID" value="<?php echo $subscriber_id; ?>"/>
    <?php endif; ?>

    <table>
        <tr>
            <td style="padding-top: 10px;">
            <!-- Name  -->
                <label for="wp_subscribe_name" class="wp_sms_subscribers_label"><?php _e('Name', 'wp-sms'); ?></label>
                <!-- <input type="text" id="wp_subscribe_name" name="wp_subscribe_name" value="<?php echo isset($subscriber->name) ? esc_attr($subscriber->name) : ''; ?>" class="wp_sms_subscribers_input_text"/> -->
            </td>
      
            <td style="padding-top: 10px;">
            <!-- Mobile -->
                <label for="wp_subscribe_mobile" class="wp_sms_subscribers_label"><?php _e('Mobile', 'wp-sms'); ?></label>
                <!-- <?php wp_sms_render_mobile_field(array('name' => 'wp_subscribe_mobile', 'class' => array('wp_sms_subscribers_input_text'), 'value' => isset($subscriber->mobile) ? esc_attr($subscriber->mobile) : '')); ?> -->
            </td>
 
            <td>
                <!-- Status -->
                <label for="wpsms_subscribe_status" class="wp_sms_subscribers_label"><?php _e('Status', 'wp-sms'); ?></label>
                <!-- <select name="wpsms_subscribe_status" id="wpsms_subscribe_status" class="wp_sms_subscribers_input_text code">';
                    <?php if (isset($subscriber)) : ?>
                        <option value="1" <?php selected($subscriber->status); ?>><?php _e('Active', 'wp-sms'); ?></option>
                        <option value="0" <?php selected($subscriber->status, false); ?>><?php _e('Deactivate', 'wp-sms'); ?></option>
                    <?php else : ?>
                        <option value="1" selected="selected"><?php _e('Active', 'wp-sms'); ?></option>
                        <option value="0"><?php _e('Deactivate', 'wp-sms'); ?></option>
                    <?php endif; ?>
                </select> -->
            </td>
        </tr>


    </table>
    <!-- Create an instance of our package class... -->
    <!-- <?php //include_once WP_SMS_DIR . 'includes/admin/subscribers/class-wpsms-subscribers-table.php'; ?>
        <?php// $list_table = new Subscribers_List_Table();

        //Fetch, prepare, sort, and filter our data...
        //$list_table->prepare_items();

        // echo \WP_SMS\Helper::loadTemplate('admin/subscribers.php', [
        //     'list_table' => $list_table,
        // ]); ?> -->
</form>

<!-- <div class="wrap wpsms-wrap-popup">

    <div class="wpsms-wrap__main-popup">
        <h2><?php // _e('Subscribers', 'wp-sms'); ?></h2>
       




        <form id="subscribers-filter" method="get">
            <?php //$_request_page = sanitize_text_field($_REQUEST['page']) ?>
            <input type="hidden" name="page" value="<?php// echo esc_attr($_request_page); ?>"/>
            <?php //$list_table->search_box(__('Search', 'wp-sms'), 'search_id'); ?>
            <?php //$list_table->display(); ?>
        </form>
    </div>
</div> -->



    <form method="post" class="js-wpSmsQuickReply" <?php if (isset($reload)) : echo 'data-reload=' . $reload; endif; ?>>

        <?php /* $list_table->display(); */ ?>  //error 
    </form>
     <!-- Subscribers class. -->
       <!-- <?php  //require_once WP_SMS_DIR . 'includes/admin/subscribers/class-wpsms-subscribers.php'; -->

        // $page = new Subscribers();
        // $page->render_page();

       ?> -->
    <div class="quick-reply-submit">
        <p class="submit" style="padding: 0;">
            <input type="submit" class="button-primary" name="SendSMS" value="<?php _e('Close', 'wp-sms'); ?>"/>
        </p>
    </div>

    <!-- <form id="subscribers-filter2" method="get"> -->
            <!-- <?php //$_request_page = sanitize_text_field($_REQUEST['page']) ?> -->
            <!-- <input type="hidden" name="page" value="<?php //echo esc_attr($_request_page); ?>"/> -->
            <?php //$list_table->search_box(__('Search', 'wp-sms'), 'search_id'); ?>
             <!-- <?php // $list_table->display(); ?>  --> -->
    </form> -->
</div>
