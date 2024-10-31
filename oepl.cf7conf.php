<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}

define('OEPL_CF7PLUGIN_URL', plugin_dir_url(__FILE__));     # define the plugin folder url
define ('OEPL_CF7PLUGIN_DIR', plugin_dir_path(__FILE__));   # define the plugin folder dir
define('OEPL_EMAIL_FIELDS', 'oepl_email_data_fields');      # Table list

require_once(OEPL_CF7PLUGIN_DIR."oepl.cls.php");
require_once(OEPL_CF7PLUGIN_DIR."oepl.function.php");	
require_once(OEPL_CF7PLUGIN_DIR."cf7_admin-functions.php");

# List all add_action
add_action('admin_menu', 'oepl_cf7d_menu');
add_action( 'wp_ajax_OEPL_cf7list_get', 'OEPL_cf7list_get_callback' );

function oepl_cf7d_menu(){
    add_menu_page('cf7database','CF7 Database', 'administrator', 'cfdatabase', 'OEPLCF7_menu_function', $icon_url = '', $position = null ); 
}
?>