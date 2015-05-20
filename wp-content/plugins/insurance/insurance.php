<?php
/*
Plugin Name: Insurance
Plugin URI:
Description: Declares a plugin that will be visible in the
WordPress admin interface
Version: 1.0
Author: Akarsh Nagariya
Author URI: http://www.mytechmagzine.net/
License: GPLv2
*/
include_once('insurance-db_activity.php');
include_once('ins_shortcode.php');

function create_ins()
{

    global $wpdb;
    $insurance = $wpdb->prefix .'insurance';
    $sql = "CREATE TABLE $insurance (
       id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
       ins_plans varchar(50) NOT NULL,
       ins_age text(200)  ,
       ins_Policy_term text(200) ,
       ins_Premium_Paying_Term text(200) ,
       ins_Sum_Assured text(200) ,
       ins_Accident INT (50) ,
       PRIMARY KEY  (id)
       );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook(__FILE__,'create_ins');
add_action('admin_init','create_ins');



add_action( 'admin_menu', 'insurance_menu' );
function insurance_menu()
{

    add_menu_page('insurance-menu', 'Insurances', 'manage_options', 'insurance-main-menu', 'insurance_func', plugins_url('img/modules_eligibility.png', __FILE__));
    //add_submenu_page('insurance-main-menu', 'ins_new','View All Insurance', 'manage_options', 'insurance-all', 'insurance_func_add');
    add_submenu_page('insurance-main-menu', 'ins_new','Add New Insurance', 'manage_options', 'insurance-add', 'insurance_func_add');
    add_submenu_page('insurance-main-menu', 'ins_settings','Settings', 'manage_options', 'insurance-set', 'insurance_func_set');
}



?>
