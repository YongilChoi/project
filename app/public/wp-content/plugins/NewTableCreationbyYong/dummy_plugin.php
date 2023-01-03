
<?php
/**
* kimchi table creation Plugin
*
* @author Yongil Choi.
*
* @wordpress-plugin
* Plugin Name: kimchi table creation Plugin
* Description: This plugin creates a new table in the database when initialized.
* Author: Yongil Choi.
* Author URI: https://www.sorynory.com
* Version: 1.0
* Requires PHP: 7.2
*/

// Create a class
class KimchiTablePlugin {

/**
* Class Constructor
* @author Yongil Choi
* @param None
* @return void
*/

public function __construct() {
// This hook will run when the plugin is activated and call the activate function
register_activation_hook(__FILE__, 'insert_kimchi_table_into_db');
}


/** This function inserts a new table into the db when the plugin is activated.
* @author Adebola
* @param None
* @return void
*/
public function insert_kimchi_table_into_db(){
    global $wpdb;
    // set the default character set and collation for the table
    $charset_collate = $wpdb->get_charset_collate();
    // Check that the table does not already exist before continuing
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}total_sent_table` (
    id bigint(50) NOT NULL AUTO_INCREMENT,
    name_id bigint(20) NOT NULL,
    tel_id bigint(20),
    success varchar(2),
    fail varchar(2),
    PRIMARY KEY (id),
    FOREIGN KEY (name_id) REFERENCES wp_posts(ID)   
    ) $charset_collate;";           //wp_posts ? 이게 뭔지 ? 
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
    $is_error = empty( $wpdb->last_error );
    return $is_error;
}

} //class end 




// Function that instantiates the class
function kimchi_table_plugin() {
new KimchiTablePlugin();
}
kimchi_table_plugin();