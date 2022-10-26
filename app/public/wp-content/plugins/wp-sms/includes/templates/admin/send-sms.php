<div class="wrap wpsms-wrap">
    <?php require_once WP_SMS_DIR . 'includes/templates/header.php'; ?>
    <div class="wpsms-wrap__main">
        <h1 class="wrap__title"><?php _e('Send SMS', 'wp-sms'); ?>
        </h1>
        <div class="wpsms-wrap__main__notice notice is-dismissible">
            <p class="wpsms-wrap__notice__text" style="padding: 10px 0; white-space: pre;"></p>
            <button type="button" onclick="closeNotice()" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
        <div class="wpsms-sendsms" style="padding-top: 4px;">
            <div class="postbox-container wpsms-sendsms__container">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <div class="inside">
                            <div class="wpsms-sendsms__overlay">
                                <svg class="wpsms-sendsms__overlay__spinner" xmlns="http://www.w3.org/2000/svg" style="margin:auto;background:0 0" width="200" height="200" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" display="block">
                                    <circle cx="50" cy="50" fill="none" stroke="#c6c6c6" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                                        <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"/>
                                    </circle>
                                </svg>
                            </div>
                            <form method="post" action="">
                                <?php wp_nonce_field('update-options'); ?>
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">
                                            <label for="wp_get_sender"><?php _e('From', 'wp-sms'); ?>:</label>
                                        </th>
                                        <td>
                                            <input type="text" name="wp_get_sender" id="wp_get_sender" value="<?php echo $smsObject->from; ?>" maxlength="18"/>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row">
                                            <label for="select_sender"><?php _e('To', 'wp-sms'); ?>:</label>
                                          

                                        </th>
                                        <td>
                                            <select name="wp_send_to" id="select_sender">
                                                <option value="subscribers" id="wp_subscribe_username"><?php _e('Subscribers', 'wp-sms'); ?>
                                                </option>
                                                <option value="users" id="wp_users"><?php _e('WordPress\'s Users', 'wp-sms'); ?>
                                                </option>
                                                <option value="wc-customers" id="wc_users" <?php disabled(!$proIsActive); ?>>
                                                    <?php _e('WooCommerce\'s Customers', 'wp-sms'); ?>
                                                    <?php if (!$proIsActive) : ?>
                                                        <span>(<?php _e('Requires Pro Pack!', 'wp-sms'); ?>)</span>
                                                    <?php endif; ?>
                                                </option>
                                                <option value="bp-users" id="bp_users" <?php disabled(!$proIsActive); ?>>
                                                    <?php _e('BuddyPress\'s Users', 'wp-sms'); ?>
                                                    <?php if (!$proIsActive) : ?>
                                                        <span>(<?php _e('Requires Pro Pack!', 'wp-sms'); ?>)</span>
                                                    <?php endif; ?>
                                                </option>
                                                <option value="numbers" id="wp_tellephone"><?php _e('Number(s)', 'wp-sms'); ?>
                                                </option>
                                            </select>

                                            <?php if (count($get_group_result)) : ?>
                                                <div class="wpsms-value wpsms-group">
                                                    <select name="wpsms_groups[]" multiple="true" class="js-wpsms-select2" data-placeholder="<?php _e('Please select the Group', 'wp-sms'); ?>">
                                                        <?php foreach ($get_group_result as $items): ?>
                                                            <option value="<?php echo $items->ID; ?>">
                                                                <?php echo sprintf(__('Group %s', 'wp-sms'), $items->name); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            <?php else: ?>
                                                <span class="wpsms-value wpsms-group" style="display: none;">
                                                <span>
                                                    <?php
                                                    global $wpdb;
                                                    $username_active = $wpdb->query("SELECT * FROM {$wpdb->prefix}sms_subscribes WHERE status = '1'");
                                                    echo sprintf(__('<b>%s</b> Subscribers.', 'wp-sms'), $username_active);
                                                    ?>
                                                </span>
                                            </span>
                                            <?php endif; ?>

                                            <span class="wpsms-value wpsms-users" style="display: none;">
                                                <span><?php echo sprintf(__('<b>%s</b> Users have the mobile number.', 'wp-sms'), count($get_users_mobile)); ?></span>
                                            </span>

                                            <div class="wpsms-value wpsms-users wpsms-users-roles">
                                                <select id="wpsms_roles" name="wpsms_roles[]" multiple="true" class="js-wpsms-select2" data-placeholder="<?php _e('Please select the Role', 'wp-sms'); ?>">
                                                    <?php
                                                    foreach ($wpsms_list_of_role as $key_item => $val_item):
                                                        ?>
                                                        <option value="<?php echo $key_item; ?>"
                                                            <?php if ($val_item['count'] < 1) {
                                                                echo " disabled";
                                                            } ?>><?php _e($val_item['name'], 'wp-sms'); ?>
                                                            (<?php echo sprintf(__('<b>%s</b> Users have mobile number.', 'wp-sms'), $val_item['count']); ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <span class="wpsms-value wpsms-wc-users" style="display: none;">
                                                <span><?php echo sprintf(__('<b>%s</b> Customers have the mobile number.', 'wp-sms'), count($woocommerceCustomers)); ?></span>
                                            </span>

                                            <span class="wpsms-value wpsms-bp-users" style="display: none;">
                                                <span><?php echo sprintf(__('<b>%s</b> Users have the mobile number in their profile.', 'wp-sms'), count($buddyPressMobileNumbers)); ?></span>
                                            </span>

                                            <span class="wpsms-value wpsms-numbers">
                                                <div class="clearfix"></div>
                                                <textarea cols="80" rows="5" style="direction:ltr;margin-top: 10px;" id="wp_get_number" name="wp_get_number"></textarea>
                                                <div class="clearfix"></div>
                                                <div style="font-size: 14px"><?php _e('Separate the numbers with comma (,) or enter in each lines.', 'wp-sms'); ?>
                                                </div>
                                                <?php if ($smsObject->validateNumber) : ?>
                                                    <div style="margin-top: 10px"><?php echo sprintf(__('Gateway description: <code>%s</code>', 'wp-sms'), $smsObject->validateNumber); ?>
                                                </div>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form>
                                                <input type="button" value="주소록" onclick="msg()">
                                            </form>

                                            <script>
                                                function msg() {
                                                alert("Hello world!");
                                            }
                                            </script>
                              
                                    
                                        </td>




                                    </tr>
                                    
                                    <tr valign="top">
                                        <th scope="row">
                                            <label for="wp_get_message"><?php _e('Message', 'wp-sms'); ?>:</label>
                                        </th>
                                        <td>
                                            <textarea dir="auto" cols="80" rows="5" wrap="hard" name="wp_get_message wpsms-input" id="wp_get_message"></textarea><br/>
                       
                                        </td>
                                        <td>
                                            <form>
                                                <input type="button" value="삽입" onclick="msg()">
                                            </form>

                                            <script>
                                                function msg() {
                                                alert("Hello world!");
                                            }
                                            </script>
                                            <style>
                                                table {
                                                    border:1px solid #b3adad;
                                                    border-collapse:collapse;
                                                    padding:5px;
                                                }
                                                table th {
                                                    border:1px solid #b3adad;
                                                    padding:5px;
                                                    background: #d6f7cf;
                                                    color: #313030;
                                                }
                                                table td {
                                                    border:1px solid #b3adad;
                                                    text-align:center;
                                                    padding:5px;
                                                    background: #ffffff;
                                                    color: #313030;
                                                }
                                            </style>
                                             <table>
                                                <thead>
                                                    <tr>
                                                        <th>선택</th>
                                                        <th>보낸날짜</th>
                                                        <th>받는사람</th>
                                                        <th>내용</th>
                                                        <th>전송실패 유무</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <form action="/action_page.php">
                                                                <input type="checkbox" id="selection" name="lineNo" value="line1">

                                                        </td>
                                                        <td> </td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td><form action="/action_page.php">
                                                                <input type="checkbox" id="selection" name="lineNo" value="line1"></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                   
                                                </tbody>
                                            </table>
                                    
                                        </td>
                                       
                                    </tr>

                            

                                                      
                              


                                    <tr>
                                        <td>
                                            <p class="submit" style="padding: 0;">
                                                <input type="submit" class="button-primary" name="SendSMS" value="<?php _e('Send SMS', 'wp-sms'); ?>"/>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
