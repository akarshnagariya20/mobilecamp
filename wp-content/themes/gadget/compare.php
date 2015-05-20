<?php
/**
 * Template Name: Compare Products
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */?>

<?php 
global $wpdb;
$compare = $wpdb->get_results( 
	"
	SELECT ID, post_title 
	FROM $wpdb->postmeta
	WHERE meta_id = 786");
foreach ( $compare as $akarsh ) 
{
	echo $akarsh->post_id;
}

  ?>

	
