<div id="wp-sms-import-from" style="display:none;">

    <!--Progress Bar-->
    <div class="wp-sms-progress-ui js-wpSmsProgressUi" style="display: none">
        <div class="wp-sms-progress-bar js-wpSmsProgressBar" style="width: 0"></div>
        <div class="wp-sms-progress-percentage js-wpSmsProgressPercentage"></div>
        <div class="wp-sms-progress-info js-wpSmsProgressInfo"></div>
        <div class="wp-sms-import-errors js-wpSmsImportErrors"></div>
    </div>

    <!--Loading Spinner-->
    <div class="js-wpSmsOverlay wpsms-sendsms__overlay">
        <svg class="wpsms-sendsms__overlay__spinner" xmlns="http://www.w3.org/2000/svg" style="margin:auto;background:0 0" width="200" height="200" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" display="block">
            <circle cx="50" cy="50" fill="none" stroke="#c6c6c6" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
            </circle>
        </svg>
    </div>

    <!-- Show request message to the client -->
    <div class="wpsms-wrap wpsms-import-popup js-wpSmsMessageModal">
        <div class="wp-sms-popup-messages js-wpSmsErrorMessage"></div>
    </div>

    <form class="js-wpSmsUploadForm wp-sms-upload-form" method="post" enctype="multipart/form-data">
        <p id="first-row-label" style="display: none"><?php _e( 'Now, please specify data type if each column.', 'wp-sms' ); ?></p>
        <table>
            <tr class="js-WpSmsHiddenAfterUpload">
                <td style="padding-top: 10px;">
                    <input type="file" accept="text/csv" id="wp-sms-input-file">
                    <p><?php _e( 'The only acceptable format is <code>*.csv.</code>', 'wp-sms' ); ?></p>
                </td>
            </tr>

            <tr class="js-WpSmsHiddenAfterUpload">
                <td style="padding-top: 10px;">
                    <input type="checkbox" id="file-has-header" class="js-wpSmsFileHasHeader">
                    <label for="file-has-header"><?php _e( 'Check the box if the file includes headers.', 'wp-sms' ); ?> </label>
                </td>
            </tr>

            <tr id="wp-sms-group-select" class="js-wpSmsGroupSelect" style="display: none">
                <td colspan="2" style="padding-top: 20px;">
                    <p><?php _e( 'Choose or add a group:', 'wp-sms' ); ?></p>
                    <select>
                        <option value="0"><?php _e( 'Please Select', 'wp-sms' ); ?></option>
                        <option value="new_group"><?php _e( 'Add a new group', 'wp-sms' ); ?></option>
						<?php
						if ( $groups ) :
							foreach ( $groups as $group ) :
								?>
                                <option value="<?php echo esc_attr( $group->ID ); ?>"><?php echo esc_attr( $group->name ); ?></option>
							<?php
							endforeach;
						endif;
						?>
                    </select>
                </td>
            </tr>

            <tr id="wp-sms-group-name" class="js-wpSmsGroupName" style="display: none">
                <td>
                    <input type="text" id="wp-sms-select-group-name" class="js-wpSmsSelectGroupName">
                </td>
            </tr>

            <tr>
                <td colspan="2" style="padding-top: 20px;">
                    <input type="submit" class="js-wpSmsUploadButton button-primary" value="<?php _e( 'Upload', 'wp-sms' ); ?>"/>
                    <input type="submit" class="js-wpSmsImportButton button-primary" style="display: none;" value="<?php _e( 'Import', 'wp-sms' ); ?>"/>
                </td>
            </tr>
        </table>
    </form>
</div>