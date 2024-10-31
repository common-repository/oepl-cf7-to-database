<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}
add_action('wpcf7_before_send_mail', 'cf7d_before_send_email_data');
function cf7d_before_send_email_data($contact_form)
{
    global $wpdb;
	$emaildata_ser = serialize($_POST);
	$date = date("Y-m-d H:i:s");
	$insert = array( 'cf7_id'      		   => sanitize_text_field($_POST['_wpcf7']),
					 'ip_address'  		   => sanitize_text_field($_SERVER['REMOTE_ADDR']),
					 'browser_information' => sanitize_text_field($_SERVER['HTTP_USER_AGENT']),
					 'form_title'         =>  sanitize_text_field($contact_form->title),
					 'form_data' 		   => sanitize_text_field($emaildata_ser),
					 'd_date'			   => sanitize_text_field($date)
					);
	$wpdb->insert(OEPL_EMAIL_FIELDS, $insert);
}
?>