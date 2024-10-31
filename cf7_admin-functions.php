<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}
##cf7 database menu START
ob_start();
function OEPLCF7_menu_function()
{
	global $wpdb, $contact_form;
	if(isset($_GET['download']) && $_GET['download'] == 'yes')
	{
		$pid = $_GET['id'];
		$query = "SELECT * FROM ".OEPL_EMAIL_FIELDS." WHERE id = ".$pid;
		$abc = $wpdb->get_row($query, ARRAY_A);
		$filename = $abc['form_title'] .'-'. $abc['id'] . '.csv';
		$csvary = array();
		$row = unserialize($abc['form_data']);
		foreach ($row as $key => $value) {
			$csvary[$key] = $value;
		}
		$csvary['cf7_id'] = $abc['cf7_id'];
		$csvary['form_title'] = $abc['form_title'];
		$csvary['ip_address'] = $abc['ip_address'];
		$csvary['date'] = $abc['d_date'];	
		$header_row = array();
		$data_values = array();
		foreach ($csvary as $arrkey => $arrvalue) 
		{
				$header_row[] = $arrkey;
				$data_values[] = $arrvalue;
		}
		// This code is check the value is in multidimensional array
		$multi_arr = array_filter($data_values,'is_array');
		if($multi_arr !== '')
		{
			foreach ($multi_arr as $multi_arrk => $multi_arrval) {
				$chkb_data = implode(',', $multi_arrval);
			}	
		}
		$data_values[$multi_arrk] = $chkb_data;
		// END
		ob_clean();
		$fh = @fopen( 'php://output', 'w' );
		fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Content-Description: File Transfer' );
		header( 'Content-type: text/csv' );
		header( "Content-Disposition: attachment; filename={$filename}" );
		header( 'Expires: 0' );
		header( 'Pragma: public' );
		print_r($fh);
		fputcsv( $fh, $header_row );
		fputcsv( $fh, $data_values);
		fclose( $fh );
		die();		
	
}else if(isset($_GET['text_down']) && $_GET['text_down'] == 'yes'){
		
		$pid = $_GET['id'];
		$query = "SELECT * FROM ".OEPL_EMAIL_FIELDS." WHERE id = ".$pid;
		$abc = $wpdb->get_row($query, ARRAY_A);
		$filename = $abc['form_title'] .'-'. $abc['id'] . '.txt';
		$csvary = array();
		$row = unserialize($abc['form_data']);
		foreach ($row as $key => $value) {
			$csvary[$key] = $value;
		}
		$csvary['cf7_id'] = $abc['cf7_id'];
		$csvary['form_title'] = $abc['form_title'];
		$csvary['ip_address'] = $abc['ip_address'];
		$csvary['date'] = $abc['d_date'];	
		$rows = array();
		foreach ($csvary as $arrkey => $arrvalue) 
		{
			$rows[$arrkey] = $arrvalue;
		}
		// This code is check the value is in multidimensional array
		$multi_arr = array_filter($rows,'is_array');
		if($multi_arr !== '')
		{
			foreach ($multi_arr as $multi_arrk => $multi_arrval) {
				$chkb_data = implode(',', $multi_arrval);
			}	
		}
		
		$rows[$multi_arrk] = $chkb_data;
		ob_clean();
		$fh = @fopen( 'php://output', 'w' );
		fwrite($fh, $csvary['form_title']);
		fwrite($fh, "\t");
		fwrite($fh, "\n");
		if(count($rows) > 0){
			foreach ($rows as $rowheader => $rowdata) {
				fwrite($fh, $rowheader);
				if($rowheader != ''){
					fwrite($fh, " : ");	
				}
				fwrite($fh, $rowdata);
				fwrite($fh, "\n");
			}	
		}
		
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Content-type: application/stream');
		header( "Content-Disposition: attachment; filename={$filename}" );
		header( 'Expires: 0' );
		header( 'Pragma: public' );
		fclose($fh);
		die();		

	}else if(isset($_GET['action']) && $_GET['action'] == 'delete'){
		
		$rec_id = $_GET['id'];
		if( $rec_id != '' ){
				$delfld = 'DELETE FROM '.OEPL_EMAIL_FIELDS.' WHERE id = '.$rec_id.' ';
				$wpdb->query($delfld);
		}
	}
    add_thickbox();
    ?>
    
    <style>
	    #cb{
	    	width: 1.2em;
	    }
    	#id {
    		width:10%;
    	}
    	#cf7_id {
    		width:40px;
    	}
    	#form_title {
    		width:50px;
    	}
    	#ip_address{
    		width:45px;
    	}
    	#d_date{
    		width:20%;
    	}
    	#form_data{
    		width:25px;
    		text-align: center !important;
    	}
    	.form_data{
    		float:right;
    	}
    </style>
    <div style="float: left" class='wrap'>
    <h1>Contact Form 7 database</h1>
        <div id="OEPL_cf7_info" style="display: none">            
            <div class="content">
            </div>
        </div>    
        <br clear="all">

	<?php
	
	wp_register_script('Opel_cf7d_JS', OEPL_CF7PLUGIN_URL .'cf7d_js/admin.js',array(),false, true);
    wp_enqueue_script( 'Opel_cf7d_JS');
		  
	echo '<form id="OEPL-cf7_table" method="get">';
	require_once(OEPL_CF7PLUGIN_DIR . 'form_table_data.php');
	$table = new OEPLCF7_table;
	echo '<input type="hidden" name="page" value="cfdatabase" />';
	$table->search_box('Search', 'cf7SearchID');
	$table->prepare_items();
	$table->display();
	echo '</form>';
	echo "</div>";
}

function OEPL_cf7list_get_callback()
{
    global $wpdb, $contact_form;
    $pid = $_POST['pid'];
    $arr_value = array();
    $crm_id = $item['id'];
    $ssql = "SELECT * FROM ".OEPL_EMAIL_FIELDS." WHERE id = ".$pid;
    $cfd = $wpdb->get_row($ssql, ARRAY_A);
    $cfd_form_data = unserialize($cfd['form_data']);
    $cf7_data=$cfd['cf7_id'];
    $cnt_cfd = count($cfd_form_data);
	
    ?>    
	<table class="custom_tbl" align="center">
	<?php
		foreach($cfd_form_data as $cfd_lbl => $cfd_data)
	    {
			if(is_array($cfd_data)){
				$cfd_data = implode(',', $cfd_data);
			}
	?>
    	<tr class="custom_th">                
			<td class="custom_td_header" text-align="left" style="padding: 0;"><?php echo $cfd_lbl; ?></td>
            <td>:</td>
            <td class="custom_td" style="padding: 3px; word-break: break-all"><?php echo $cfd_data.'<br/>';?>            </td>                
		</tr>            
    	<?php
		}
		?>    
    </table>
    <?php
    die();
}
?>