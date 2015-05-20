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
function create_ins()
{
    global $wpdb;
    $insurance = $wpdb->prefix .'insurance';
    $sql = "CREATE TABLE $insurance (
       id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
       ins_plans varchar(50) NOT NULL,
       ins_age INT (50) ,
       ins_Policy_term INT (50) ,
       ins_Premium_Paying_Term INT (50) ,
       ins_Sum_Assured INT (50) ,
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
 function insurance_func_add()
 {
     ?>
     <form method="post" action="">
         <table>
             <tr>
                 <td style="width: 150px">Plan Name</td>
                 <td><input type="text" name="ins_plans" size="60" /></td>
             </tr>
             <tr>
                 <td>Age</td>
                 <td><input type="text" name="ins_age" size="10"/></textarea></td>
             </tr>
             <tr>
                 <td>Policy Term
                 </td>
                 <td><input type="text" size="10" name="ins_policy"/></td>
             </tr>
             <tr>
                 <td>Premium Paying Term
                 </td>

                 <td><input type="text" size="10" name="ins_term"/></td>

             </tr>
             <tr>
                 <td>Accident Term Check
                 </td>

                 <td>
                     <select name="accident">
                         <option  value="1">Checked</option>
                         <option value="0">Unchecked</option>
                     </select>
                 </td>

             </tr>
         </table>
         <input type="submit" value="Submit" class="button-primary"/>
     </form>

 <?php
 if(@$_POST){
$insplans = $_POST['ins_plans'];
     $insage = $_POST['ins_age'];
     $inspolicy = $_POST['ins_policy'];
     $insterm = $_POST['ins_term'];
     $accident = $_POST['accident'];

     global $wpdb;
     $insurance1 = $wpdb->prefix .'insurance';
     $wpdb->insert($insurance1, array(
         "ins_plans" => $insplans,
         "ins_age" => $insage,
         "ins_Policy_term" => $inspolicy,
         "ins_Premium_Paying_Term" => $insterm,
         "ins_Accident" => $accident

     ));
 }


 }

?>
