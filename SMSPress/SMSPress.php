<?php
/*
Plugin Name:SMSPress
Plugin URI: http://okeowoaderemi.com
Description: A Wordpress Plugin that makes it easy to send SMS Credit also goes to Chirag Kalani for i used his plugin as a base and rewrote some classes
Version:0.1 Beta
Author: Okeowo Aderemi,Tokunbo Igeh
Author URI: http://okeowoaderemi.com
License:GPL2
  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
 add_action('admin_menu','includeClientScripts');
register_activation_hook( __FILE__, 'smspress_setup' );
register_deactivation_hook( __FILE__, 'smspress_close' );
function SMSPressMenu() {
	add_menu_page( 'SMSPress Setup', 'SMSPress Setup', 'manage_options', 'smspress', 'sms_setup');
	//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
	add_submenu_page( 'smspress', 'Sent SMS', 'Sent SMS', 'manage_options', 'smspress-sent', 'renderSentSMS');
	add_submenu_page( 'smspress', 'Send SMS', 'Send SMS', 'manage_options', 'smspress-send', 'renderSendSMS');
	

}
function renderSendSMS(){
	
	require 'ui-form.php';
}
function smspress_setup(){
//Add some default values
$sqlQuery="CREATE TABLE IF NOT EXISTS 'wp_smspress_sentsms' (
  'id' int(11) NOT NULL AUTO_INCREMENT,
  'recipent' varchar(300) NOT NULL,
  'subject' varchar(40) NOT NULL,
  'message' text NOT NULL,
  'time_sent' datetime NOT NULL,
  PRIMARY KEY ('id')
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
//query the SQL
global $wpdb;

$wpdb->query($sqlQuery);	
}
function smspress_close(){
//Handle the Shutting down of the Script plugin	
}
function sms_setup(){
	
	require('ui-admin.php');
	
}
function renderSentSMS(){

require 'classes/SentSMS.php';
	
$listTable=new List_SentSMS();

echo '<form method="get"><div class="wrap"><h2>SMSPress-Sent SMS</h2>';
echo '<input type="hidden" name="page" value="'.$_REQUEST['page'].'" />';
$listTable->prepare_items();
$listTable->search_box('search', 'search-submit');
$listTable->display();
echo '</div></form>';
	
}
function includeClientScripts(){
$file=get_bloginfo("wpurl")."/wp-content/plugins/SMSPress/js/dojo.js";	
wp_register_script('dojo',$file);
wp_enqueue_script('dojo');	
}

function renderSavedContacts(){
	require 'classes/SavedContacts.php';
	$listTable=new List_SavedContacts();
	echo '<form method="get"><div class="wrap"><h2>SMSPress-Saved Contacts</h2>';
	echo '<input type="hidden" name="page" value="'.$_REQUEST['page'].'" />';
	$listTable->prepare_items();
	$listTable->search_box('search', 'search-submit');
	$listTable->display();
	echo '</div></form>';

}

function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
}
add_action("admin_menu","SMSPressMenu");

