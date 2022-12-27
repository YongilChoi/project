<?php
namespace WP_SMS;

$groups = Newsletter::getGroups();
?>

<div class="wrap wpsms-wrap">
    <?php echo Helper::loadTemplate('header.php'); ?>
    <div class="wpsms-wrap__main">
        <h2><?php _e('Subscribers', 'wp-sms'); ?></h2>
        <?php echo Helper::loadTemplate('admin/quick-reply.php'); ?>
        <div class="wpsms-button-group">
            <a name="<?php _e('Add Subscriber', 'wp-sms'); ?>" href="admin.php?page=wp-sms-subscribers#TB_inline?&width=400&height=250&inlineId=add-subscriber" class="thickbox button"><span class="dashicons dashicons-admin-users"></span> <?php _e('Add Subscriber', 'wp-sms'); ?></a>
            <a href="admin.php?page=wp-sms-subscribers-group" class="button"><span class="dashicons dashicons-category"></span> <?php _e('Manage Group', 'wp-sms'); ?></a>
            <a name="<?php _e('Import', 'wp-sms'); ?>" href="admin.php?page=wp-sms-subscribers#TB_inline?&width=400&height=270&inlineId=wp-sms-import-from" class="thickbox button"><span class="dashicons dashicons-undo"></span> <?php _e('Import', 'wp-sms'); ?></a>
            <a name="<?php _e('Export', 'wp-sms'); ?>" href="admin.php?page=wp-sms-subscribers#TB_inline?&width=400&height=150&inlineId=wp-sms-export-from" class="thickbox button"><span class="dashicons dashicons-redo"></span> <?php _e('Export', 'wp-sms'); ?></a>
        </div>

        <div id="add-subscriber" style="display:none;">
            <?php echo Helper::loadTemplate('admin/subscriber-form.php', array('groups' => $groups)); ?>
        </div>

        <?php

        echo Helper::loadTemplate('admin/import-subscriber-form.php', array(
            'groups' => $groups,
        ));

        echo Helper::loadTemplate('admin/export-subscriber-form.php', array(
            'groups' => $groups,
        ));

        ?>

        <form id="subscribers-filter" method="get">
            <?php $_request_page = sanitize_text_field($_REQUEST['page']) ?>
            <input type="hidden" name="page" value="<?php echo esc_attr($_request_page); ?>"/>
            <?php $list_table->search_box(__('Search', 'wp-sms'), 'search_id'); ?>
            <?php $list_table->display(); ?>
        </form>
    </div>
</div>





<!-- 구독자 목록을 가져온다. -->












    <form method="post" class="js-wpSmsQuickReply" <?php if (isset($reload)) : echo 'data-reload=' . $reload; endif; ?>>
        <table>
            <tr>
                <td style="padding-top: 10px;">
                    <label for="wpsms-quick-reply2-to"><?php _e('To', 'wp-sms'); ?></label>
                    <input type="text" id="wpsms-quick-reply-to" class="js-wpSmsQuickReplyTo" name="wpsms_quick_reply_message" value="" readonly style="display: block; width: 100%"/>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 10px;">
                    <label for="wpsms-quick-reply-message"><?php _e('Message', 'wp-sms-two-way'); ?></label>
                    <textarea id="wpsms-quick-reply-message" class="js-wpSmsQuickReplyMessage" name="wpsms_quick_reply_message" cols="60" rows="10" wrap="hard" dir="auto" style="width: 100%"></textarea>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 10px;">
                    <label for="wpsms-quick-reply-message"><?php _e('Message', 'wp-sms-two-way'); ?></label>
                    <textarea id="wpsms-quick-reply-message" class="js-wpSmsQuickReplyMessage" name="wpsms_quick_reply_message" cols="60" rows="10" wrap="hard" dir="auto" style="width: 100%"></textarea>
                </td>
            </tr>

        </table>
    </form>
    

    //--
</div>
