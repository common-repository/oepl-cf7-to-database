<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}
class OEPLCF7_table extends WP_List_Table 
{
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'Lead',     //singular name of the listed records
            'plural'    => 'Leads',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
    }
    function column_default($item, $column_name)
    {	
    	$old_date = $item['d_date'];
		$old_date_timestamp = strtotime($old_date);
		$new_date = date('d-M-Y H:i:s', $old_date_timestamp);   
        switch($column_name){
            case 'id':
            	return $item['id'];
			
			case 'cf7_id':
				return $item['cf7_id'];
			
			case 'ip_address':
                return $item['ip_address'];
            
            case 'form_title':
				return $item['form_title'];
            
            case 'd_date':
				$datef = get_option( 'date_format' );
				$timef = get_option( 'time_format' );
				$datetime = $datef.' '.$timef;
				return mysql2date($datetime,$item['d_date']);            
            case 'form_data':
                 return '<a href="#"><img class="oepl_cf7formlist" pid="'.$item['id'].'" frm="'.$item['form_title'].'" src="'.OEPL_CF7PLUGIN_URL.'images/View_Details.png" title="Detail view" style="width:33px; height:35px; margin-right:10px;"></a>'.'<a href="'.admin_url().'admin.php?page=cfdatabase&download=yes&id='.$item['id'].'"><img src="'.OEPL_CF7PLUGIN_URL.'images/csv.png" title="Download csv file" style="width:33px; height:35px;"></a>'.'<a href="'.admin_url().'admin.php?page=cfdatabase&text_down=yes&id='.$item['id'].'"><img src="'.OEPL_CF7PLUGIN_URL.'images/txtfile.png" title="Download text file" style="width:32px; height:37px;"></a>'.'<a href="'.admin_url().'admin.php?page=cfdatabase&action=delete&id='.$item['id'].'"><img src="'.OEPL_CF7PLUGIN_URL.'images/del.png" title="Delete this record" class="delete_rec" style="width:33px; height:35px;"></a>';
			
			case '$hidden':
				return $new_date;
            
            default:
                return 'No Data'; //Show the whole array for troubleshooting purposes
        }
    }
    function column_cb( $item ) 
    {
	  return sprintf(
	    '<input type="checkbox" name="flddelete[]" value="%s" />', $item['id']
	  );
	}
    function get_columns()
    {
        $columns = array( 'cb'        	=> '<input type="checkbox" />',
        				  'id'			=> 'ID',
				          'cf7_id'		=> 'CF7 ID',
				          'form_title' 	=> 'Form Title',
				          'ip_address' 	=> 'IP',
				          'd_date'		=> 'Created Date',
				          'form_data'	=> 'Action',
				        );
        return $columns;
    }
	function get_sortable_columns() 
	{
        $sortable_columns = array(
            'id'	=> array('id'),     
            'form_title'	=> array('form_title'),		//true means it's already sorted
            'd_date'    	=> array('d_date'),			//true means it's already sorted
			'cf7_id'    	=> array('cf7_id'),
			'ip_address'    	=> array('ip_address'),
		);
        return $sortable_columns;
    }
    function get_bulk_actions() 
    {
        $actions = array( 'flddelete' => 'Delete' );
        return $actions;
	}
	public function search_box( $text, $input_id ) {
	?>
	<p class="search-box">
	<label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:
	</label>
	<input type="search" id="<?php echo $input_id ?>" name="s" value="" / >
	<?php submit_button($text, 'button', '', false, array('id' => 'search-submit')); ?>
	</p><?php }

	function process_bulk_action()
	{
		global $wpdb;
		$redirectFlag = FALSE;
		if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'flddelete' ) || ( isset( $_GET['action2'] ) && $_GET['action2'] == 'flddelete' ) )
		{
			
			$delete_ids = $_GET['flddelete'];
			
			if( $delete_ids != '' ){
				
				foreach ( $delete_ids as $id ) {
					$dltfld = 'DELETE FROM '.OEPL_EMAIL_FIELDS.' WHERE id = '.$id.' ';
					$wpdb->query($dltfld);
				}
				$redirectFlag = TRUE;	
			}
		}
		if($redirectFlag == TRUE)
		{
			if($_GET['orderby']) $orderby = '&orderby='.$_GET['orderby']; 	else $orderby = '';
			if($_GET['order']) $order = '&order='.$_GET['order'];			else $order = '';
			$url = admin_url('admin.php?page=cfdatabase'.$orderby.$order);
			wp_redirect($url);
			exit;
		}
	}
	function prepare_items()
	{
		global $wpdb;
		$per_page = 10;
		$columns = $this->get_columns();
		$hidden = array();
		$getdata = 'SELECT * FROM '.OEPL_EMAIL_FIELDS;
		
		if(!empty($_GET['s']))
		{
			
			$where = ' WHERE form_title LIKE "%'.$_GET['s'].'%"
						OR
						id LIKE "%'.$_GET['s'].'%" 
						OR
						ip_address LIKE "%'.$_GET['s'].'%"
						OR
						cf7_id LIKE "%'.$_GET['s'].'%"  ';
			$getdata .= $where;
		}
	
		$orderby 	= !empty($_GET["orderby"]) 	? $_GET["orderby"]: 'ASC';
		$order 		= !empty($_GET["order"]) 	? $_GET["order"]:'';
		if(!empty($orderby) & !empty($order)) {
			$getdata .=' ORDER BY '.$orderby.' '.$order;
		} else {
			$getdata .= ' ORDER BY id ASC,form_title ASC,d_date ASC';
		}
		$data = $wpdb->get_results($getdata,ARRAY_A);
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable	);
		$this->process_bulk_action();
		$current_page = $this->get_pagenum();
		$total_items = count($data);
		$data = array_slice($data,(($current_page-1)*$per_page),$per_page);
		$this->items = $data;
		$this->set_pagination_args( array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil($total_items/$per_page),
				));
	}
}
?>