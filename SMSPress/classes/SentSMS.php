<?php
//Load the Class List Plugin to use
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class List_SentSMS extends WP_List_Table{
		
	
	function __construct(){
        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'item',     //singular name of the listed records
            'plural'    => 'items',    //plural name of the listed records
            'ajax'      => true        //does this table support ajax?
        ) );

    }
    
	
	
	function get_columns(){
		$cols=array(
		'cb'=>'<input type="checkbox" />',
		"id"=>"No",
		"recipent"=>"Recipent",
		"subject"=>"Subject",
		"message"=>"Message",
		"time_sent"=>"Time Sent");
		return $cols;
	}
	function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'recipent':
			case 'subject':
			case 'message':
			case 'time_sent';
			case 'id';
				return $item->$column_name;
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
	function get_bulk_actions(){
		return $actions=array("delete"=>"Delete");
		
	}
	
	function column_cb($item) {
		return sprintf(
				'<input type="checkbox" name="user[]" value="%s" />', $item->id
		);
	}
	function admin_header() {
		$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if( 'my_list_test' != $page )
			return;
		echo '<style type="text/css">';
		echo '.wp-list-table .column-id { width: 5%; }';
		echo '.wp-list-table .column-booktitle { width: 40%; }';
		echo '.wp-list-table .column-author { width: 35%; }';
		echo '.wp-list-table .column-isbn { width: 20%; }';
		echo '</style>';
	}
	function no_items() {
		_e( 'No Data found, dude.' );
	}
	function column_id($item) {
		$actions = array(
				'delete'    => sprintf('<a href="?page=%s&action=%s&userid=%s">Delete</a>',$_REQUEST['page'],'delete',$item->id),
		);
		return sprintf('%1$s %2$s', $item->recipent, $this->row_actions($actions) );
	}
	
	
	function get_sortable_columns(){
		$sortable_columns = array(
				'id'  => array('id',false),
				'recipent' => array('recipent',false),
				'time_sent'   => array('time_sent',false)
		);
		
		return $sortable_columns;
	}
	function process_bulk_action() {
	
		//Detect when a bulk action is being triggered...
		if( 'delete'===$this->current_action() ) {
			$deletable=$_REQUEST['user'];
			
			global $wpdb;
			foreach($deletable as $deleted) 
			{
				$wpdb->query('delete from wp_smspress_sentsms where id='.$deleted);
			}
            //wp_die('Items deleted (or they would be if we had items to delete)!');
			$this->notify="Successfully Deleted";
			wp_redirect($_REQUEST['_wp_referrer']);
		}
	
	}
	
	function prepare_items(){
	//Debugging Purposes
	
	$columns=$this->get_columns();
	$hidden=array();
	
	$sortable=$this->get_sortable_columns();
	//Change or Alter the Data
	$this->process_bulk_action();
	$this->_column_headers=array($columns,$hidden,$sortable);
	//Fetch the Data from the Database
	global $wpdb;
	/*Ordering Function*/
	$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'time_sent'; //If no sort, default to title
	$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'ASC'; //If no order, default to asc
	$query='select * from wp_smspress_sentsms order by '. $orderby . ' ' .$order;
	
	/*Pagination*/
	$total_items=$wpdb->get_var($wpdb->prepare("SELECT count(*) FROM `wp_smspress_sentsms`"));
	$perpage = 5;
	//Which page is this?
	$paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
	//Page Number
	if(empty($paged) || !is_numeric($paged) || $paged<=0 ){
		$paged=1;
	}
	$this->items=$db_result;	
	$current_page = $this->get_pagenum();
	$totalPages=ceil($total_items / $perpage);
	// only ncessary because we have sample data
	//adjust the query to take pagination into account
        if(!empty($paged) && !empty($perpage)){
            $offset=($paged-1) * $perpage;
            $query.=' LIMIT '.(int)$offset.','.(int)$perpage;

        }
	
	/* -- Register the pagination -- */

        $this->set_pagination_args( array(
            "total_items" => $total_items,
            "total_pages" => $totalPages,
            "per_page" => $perpage,
        ) );
    $d=array(
            "total_items" => $total_items,
            "total_pages" => $totalPages,
            "per_page" => $perpage,
        );
       
	$this->items =$wpdb->get_results($query,OBJECT);
	}
}
