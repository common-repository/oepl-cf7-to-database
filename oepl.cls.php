<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}
class OEPL_CF7D_ContactClass
{
	function cfdInstall()
	{
		global $wpdb;
		$emaildatbase =  "CREATE TABLE `".OEPL_EMAIL_FIELDS."` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `cf7_id` int(11) NOT NULL,
						  `ip_address` varchar(50) NOT NULL,
						  `browser_information` varchar(250) NOT NULL,
						  `form_title` varchar(250) NOT NULL,
						  `form_data` text,
						  `d_date`  datetime,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		$wpdb->query($emaildatbase);
	}
	function cfdUnInstall() 
	{
		global $wpdb;
		$sql = "DROP TABLE ".OEPL_EMAIL_FIELDS." ";
		$wpdb->query($sql);
	}
}
?>