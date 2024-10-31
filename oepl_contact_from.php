<?php
/*
Plugin Name: Contact Form7 Database
Description: This plugin will help you to save data into database when user will submit CF7 forms.
Version: 1.2
Author: Offshore Evolution Pvt Ltd
Author URI: http://www.offshoreevolution.com/
License: GPL
*/
require_once("oepl.cf7conf.php");
/* Runs when plugin is activated */
register_activation_hook(__FILE__, 'WPOEPLcfdInstall');
/* Runs on plugin deactivation*/
register_deactivation_hook(__FILE__, 'WPOEPLcfdUninstall');

function WPOEPLcfdInstall() 
{
	if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' )) {

        wp_die('Sorry, but this plugin requires the Contact Form 7 Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }else{
    	$ins = new OEPL_CF7D_ContactClass;
    	$ins->cfdInstall();	
    }
    
}
function WPOEPLcfdUninstall() {
	
	$ins = new OEPL_CF7D_ContactClass;
	$ins -> cfdUnInstall();
}
?>