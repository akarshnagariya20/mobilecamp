<?php if (!defined('APS_VER')) exit('restricted access');
/*
 * @package WordPress
 * @subpackage APS Products
*/
	// add action register our post type aps-products
	add_action( 'init', 'register_cpt_aps_products' );

	// Register our Custom Post type as aps-products
	function register_cpt_aps_products() {
		$settings = get_aps_settings();
		$slug = (isset($settings['product-slug'])) ? $settings['product-slug'] : 'product';
		
		// labels text for our post type aps-products
		$labels = array(
			// post type general name
			'name' => __( 'Products', 'aps-text' ),
			// post type singular name
			'singular_name' => __( 'Product', 'aps-text' ),
			'name_admin_bar' => __( 'Product', 'aps-text' ),
			'menu_name' => __( 'APS Products', 'aps-text' ),
			'add_new' => __( 'Add New Product', 'aps-text' ),
			'add_new_item' => __( 'Add New Product', 'aps-text' ),
			'edit_item' => __( 'Edit Product', 'aps-text' ),
			'new_item' => __( 'New Product', 'aps-text' ),
			'view_item' => __( 'View Product', 'aps-text' ),
			'search_items' => __( 'Search Products', 'aps-text' ),
			'not_found' =>  __( 'No Products found', 'aps-text' ),
			'not_found_in_trash' => __( 'No Products found in Trash', 'aps-text' )
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'show_in_nav_menus' => false,
			'menu_icon' => 'dashicons-products',
			'capability_type' => 'post',
			'hierarchical' => false,
			'taxonomies' => array('aps-brands'),
			'has_archive' => true,
			'menu_position' => 6,
			'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
			'rewrite' => array('slug' => $slug, 'with_front' => false)
		);	
		register_post_type( 'aps-products', $args );
	}
	
	//add filter to insure the text APS Products, is displayed when user updates
	add_filter( 'post_updated_messages', 'aps_products_updated_messages' );

	function aps_products_updated_messages( $messages ) {
		global $post;
		
		$messages['aps-products'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __( 'Product updated. <a href="%s">View Product</a>', 'aps-text' ), esc_url( get_permalink( $post->ID) ) ),
			2 => __( 'Custom field updated.', 'aps-text' ),
			3 => __( 'Custom field deleted.', 'aps-text' ),
			4 => __( 'Product updated.', 'aps-text' ),
			// translators: %s: date and time of the revision
			5 => isset( $_GET['revision'] ) ? sprintf( __( 'Product restored to revision from %s', 'aps-text' ), wp_post_revision_title( ( int ) $_GET['revision'], false ) ) : false,
			6 => sprintf( __( 'Product published. <a href="%s">View Product</a>', 'aps-text' ), esc_url( get_permalink( $post->ID) ) ),
			7 => __( 'Product saved.', 'aps-text' ),
			8 => sprintf( __( 'Product submitted. <a target="_blank" href="%s">Preview Product</a>', 'aps-text' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) ),
			9 => sprintf( __( 'Product scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Product</a>', 'aps-text' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post->ID) ) ),
			10 => sprintf( __( 'Product draft updated. <a target="_blank" href="%s">Preview Product</a>', 'aps-text' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID) ) ) ),
		);
		return $messages;
	}
	
	// custom taxonomy hook into the init action
	add_action( 'init', 'create_aps_taxonomies', 0 );

	// create brands taxonomy for the post type "aps-product"
	function create_aps_taxonomies() {
		$settings = get_aps_settings();
		$slug = (isset($settings['brand-slug'])) ? $settings['brand-slug'] : 'brand';
		
		// Add new taxonomy, NOT hierarchical (like tags)
		$labels = array(
			'name' => __( 'Brands', 'aps-text' ),
			'singular_name' => __( 'Brand', 'aps-text' ),
			'search_items' => __( 'Search Brands', 'aps-text' ),
			'popular_items' => __( 'Popular Brands', 'aps-text' ),
			'all_items' => __( 'All Brands', 'aps-text' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Brand', 'aps-text' ),
			'update_item' => __( 'Update Brand', 'aps-text' ),
			'add_new_item' => __( 'Add New Brand', 'aps-text' ),
			'new_item_name' => __( 'New Brand Name', 'aps-text' ),
			'separate_items_with_commas' => __( 'Separate Brands with commas', 'aps-text' ),
			'add_or_remove_items' => __( 'Add or remove Brands', 'aps-text' ),
			'choose_from_most_used' => __( 'Choose from the most used Brands', 'aps-text' ),
			'not_found' => __( 'No Brands found.', 'aps-text' ),
			'menu_name' => __( 'APS Brands', 'aps-text' )
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'has_archive' => true,
			'meta_box_cb' => 'aps_products_brands_meta_box',
			'rewrite' => array( 'slug' => $slug, 'with_front' => false )
		);
		
		register_taxonomy( 'aps-brands', 'aps-products', $args );
	}
	
	// only display aps-brands taxonomy on aps-products listings
	add_action( 'restrict_manage_posts', 'only_show_aps_brands' );

	function only_show_aps_brands() {
		global $typenow;
		
		if ( $typenow == 'aps-products' ) {
			
			$filters = array( 'aps-brands' );
			
			foreach ( $filters as $tax_slug ) {
				// retrieve the taxonomy object
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				// retrieve array of term objects per taxonomy
				$terms = get_terms( $tax_slug );
				
				// output html for taxonomy dropdown filter
				echo '<select name="' .$tax_slug .'" id="' .$tax_slug .'" class="postform">';
				echo '<option value="">Show All ' .$tax_name .'</option>';
				foreach ( $terms as $term ) {
					// output each select option line, check against the last $_GET to show the current option selected
					echo '<option value="'. $term->slug .'"', $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' .$term->name .' (' .$term->count .')</option>';
				}
				echo '</select>';
			}
		}
	}
	
	// callback function for aps-brands taxonomy metabox
	function aps_products_brands_meta_box( $post, $box ) {
		$taxonomy = 'aps-brands'; ?>
		<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
			<div id="<?php echo $taxonomy; ?>-list">
				<?php // get aps-brands terms
				$brands = get_terms($taxonomy, 'hide_empty=0');
				$name = 'tax_input[' .$taxonomy .']';
				$current_brand = get_product_brand($post->ID);
				if ($brands) { ?>
					<select name="<?php echo $name; ?>" class="widefat">
						<option value="0">--- <?php _e('Select A Brand', 'aps-text'); ?> ---</option>
						<?php foreach ($brands as $brand) { ?>
							<option value="<?php echo $brand->slug; ?>"<?php if ($brand->term_id == $current_brand->term_id ) echo ' selected="selected"'; ?>><?php echo $brand->name; ?></option>
						<?php } ?>
					</select>
				<?php } ?>
			</div>
		</div>
		<?php
	}
	
	// add action for customize aps-products columns layout
	add_filter( 'manage_edit-aps-products_columns', 'aps_products_edit_columns' );
	add_action( 'manage_posts_custom_column',  'aps_products_custom_columns' );

	function aps_products_edit_columns( $columns ) {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title', 'aps-text'),
			'image' => __('Image', 'aps-text'),
			'taxonomy-aps-brands' => __('APS Brands', 'aps-text'),
			'rating' => __('Rating', 'aps-text'),
			'comments' => '<span class="vers"><span class="comment-grey-bubble" title="Reviews"></span></span>',
			'date' => __('Date', 'aps-text')
		);
		return $columns;
	}
	
	// edit default columns add our custom columns
	function aps_products_custom_columns( $column ) {
		global $post;
		
		switch ( $column ) {
			case 'image' :
				$image = get_product_image(80, 80);
				echo '<img src="' .$image['url'] .'" alt="" />';
			break;
			case 'rating' :
				$rating = get_product_rating_total($post->ID);
				echo '<strong>' .$rating .'</strong>';
			break;
		}
	}

	// aps-products meta box hook into WordPress
	add_action( 'admin_init', 'add_aps_product_metaboxes' );

	// Add meta boxses
	function add_aps_product_metaboxes() {
		add_meta_box( 'aps_product_meta_box', __( 'APS Product Data', 'aps-text' ), 'create_aps_product_metabox', 'aps-products', 'normal', 'core' );
	}
	
	// aps-product data meta box
	function create_aps_product_metabox() {
		global $post;
		
		// generate HTML for our meta box ?>
		<div class="admin-inside-box clearfix">
			<div class="aps-wrap">
				<input type="hidden" name="aps_product_meta_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>" />
				<ul class="aps-data-tabs">
					<li class="active" data-tab="#aps-tb-features"><?php _e('Features', 'aps-text'); ?></li>
					<li data-tab="#aps-tb-gallery"><?php _e('Gallery', 'aps-text'); ?></li>
					<li data-tab="#aps-tb-videos"><?php _e('Videos', 'aps-text'); ?></li>
					<li data-tab="#aps-tb-ratings"><?php _e('Ratings', 'aps-text'); ?></li>
					<li data-tab="#aps-tb-attributes"><?php _e('Attributes', 'aps-text'); ?></li>
					<li data-tab="#aps-tb-filters"><?php _e('Filters', 'aps-text'); ?></li>
					<li data-tab="#aps-tb-offers"><?php _e('Offers', 'aps-text'); ?></li>
					<li data-tab="#aps-tb-tabs"><?php _e('Tabs', 'aps-text'); ?></li>
				</ul>
				
				<div class="aps-tabs-container">
					<div id="aps-tb-features" class="aps-tab-content">
						<ul class="aps-inputs">
							<?php // get main features
							$main_features = get_aps_features();
							$features = get_aps_product_features($post->ID);
							foreach ($main_features as $feature_key => $feature) { ?>
								<li>
									<label for="<?php echo $feature_key; ?>"><?php echo $feature['name']; ?></label>
									<input type="text" class="aps-text-input" id="<?php echo $feature_key; ?>" name="aps-features[<?php echo $feature_key; ?>]" value="<?php echo $features[$feature_key]; ?>" />
								</li>
							<?php } ?>
						</ul>
					</div>
					
					<div id="aps-tb-gallery" class="aps-tab-content">
						<?php // get aps gallery saved metadata
						$gallery_data = get_aps_product_gallery($post->ID); ?>
						<div class="field-hidden" style="display:none;">
							<div class="aps-image">
								<img src="" alt="" />
								<input type="hidden" name="aps-dynamic" value="" />
								<a class="delete-field aps-btn-del" href="#"><span class="dashicons dashicons-dismiss"></span></a>
							</div>
							<a class="add-image button" href="#"><?php _e('Select Image', 'aps-text'); ?></a>
						</div>
						<div class="aps-gallery">
							<a href="#" class="aps-btn aps-btn-green add-field"><i class="dashicons aps-icon-plus"></i> <?php _e('Add Image', 'aps-text'); ?></a>
						</div>
					</div>
					
					<div id="aps-tb-videos" class="aps-tab-content">
						<p>Add videos about your product hosted on popular video sites, click on Add Video button select host and enter the video ID.</p>
						<?php // get aps videos saved metadata
						$videos_data = get_aps_product_videos($post->ID);
						
						// make an array of top video hosting sites
						$video_hosts = array(
							'youtube' => 'YouTube',
							'dailymotion' => 'Daily Motion',
							'vimeo' => 'Vimeo',
							'break' => 'Break',
							'metacafe' => 'MetaCafe'
						); ?>
						<ul class="aps-sortable aps-fields-list aps-vid-sorting"></ul>
						<div class="video-hidden" style="display:none;">
							<div class="aps-box-inside">
								<span class="tb-title"><span class="dashicons dashicons-menu"></span></span>
								<div class="aps-col-3">
									<label><?php _e('Video Host', 'aps-text'); ?></label>
									<select class="aps-select-box video-host" name="aps-dynamic">
										<?php foreach ($video_hosts as $host_key => $host_name) { ?>
											<option value="<?php echo $host_key; ?>"><?php echo $host_name; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="aps-col-3">
									<label><?php _e('Video ID', 'aps-text'); ?></label>
									<input type="text" class="aps-text-input video-id" name="aps-dynamic" value="" />
								</div>
								<a class="delete-video aps-btn-del" href="#"><span class="dashicons dashicons-dismiss"></span></a>
							</div>
						</div>
						<div class="aps-videos">
							<a href="#" class="aps-btn aps-btn-green add-video"><i class="aps-icon-plus"></i> <?php _e('Add Video', 'aps-text'); ?></a>
						</div>
					</div>
					
					<div id="aps-tb-ratings" class="aps-tab-content">
						<?php // get aps product rating bars
						$rating_bars = get_aps_rating_bars();
						$rating = get_product_rating($post->ID); ?>
						<ul class="rating-list">
							<li>
								<p><strong><?php _e('Over all Rating', 'aps-text'); ?></strong> <span class="aps-total-score"></span> / 10</p>
							</li>
							<?php foreach ($rating_bars as $key => $bar) { ?>
								<li>
									<label><?php echo $bar['label']; ?>:</label>
									<input type="text" name="aps-rating[<?php echo $key; ?>]" data-slider="true" data-slider-range="0,10" data-slider-step="1" data-slider-snap="true" data-slider-highlight="true" data-slider-theme="aps" value="<?php echo (isset($rating[$key]) ? $rating[$key] : $bar['value']); ?>" />
								</li>
							<?php } ?>
						</ul>
					</div>
					
					<div id="aps-tb-attributes" class="aps-tab-content">
						<?php // get aps groups
						$groups = get_aps_groups();
						
						// get aps product attributes
						$attributes = get_aps_attributes();
						
						if (!empty($groups)) {
							$li = 0; ?>
							<ul class="aps-data-pils">
								<?php foreach ($groups as $group_key => $group) { ?>
									<li data-pil="#aps-pil-<?php echo $group_key; ?>"<?php if ($li == 0) echo ' class="active"'; ?>><?php echo $group['name']; ?></li>
									<?php $li++;
								} ?>
							</ul>
							<div class="aps-pil-container">
								<?php foreach ($groups as $group_key => $group) {
									$data = get_aps_product_attributes($post->ID, $group_key); ?>
									<div id="aps-pil-<?php echo $group_key; ?>" class="aps-pil-content">
										<h2><i class="aps-icon-<?php echo $group['icon']; ?>"></i> <?php echo $group['name']; ?></h2>
										<ul class="aps-inputs">
											<?php foreach ($attributes[$group_key] as $key => $field) {
												$value = (isset($data[$key])) ? $data[$key] : null; ?>
												<li>
													<?php // print input fields
													echo '<label for="' .$group_key .'-' .$key .'">' .$field['name'] .'</label>';
														
													// switch the input types
													switch ($field['type']) {
														case 'text' :
															// make text input field
															echo '<input type="text" name="aps-attr[' .$group_key .'][' .$key .']" id="' .$group_key .'-' .$key .'" class="aps-text-input" value="' .$value .'" />';
														break;
															
														case 'check' :
															// make checkbox input field
															echo '<input type="checkbox" name="aps-attr[' .$group_key .'][' .$key .']" id="' .$group_key .'-' .$key .'" class="aps-checkbox" value="Yes"' .(($value == 'Yes') ? ' checked="checked"' : '') .' />';
														break;
															
														case 'date' :
															// make date input field
															echo '<input type="text" name="aps-attr[' .$group_key .'][' .$key .']" id="' .$group_key .'-' .$key .'" class="aps-date-input aps-text-input" value="' .$value .'" />';
														break;
															
														case 'textarea' :
															// make textarea input field
															echo '<textarea name="aps-attr[' .$group_key .'][' .$key .']" id="' .$group_key .'-' .$key .'" class="aps-textarea" rows="4">' .$value .'</textarea>';
														break;
															
														case 'select' :
															// make select box
															echo '<select name="aps-attr[' .$group_key .'][' .$key .']" id="' .$group_key .'-' .$key .'" class="aps-select-box">';
															foreach ($field['options'] as $option) {
																echo '<option value="' .$option .'"' .(($option == $value) ? ' selected="selected"' : '') .'>' .$option .'</option>';
															}
															echo '</select>';
														break;
													} ?>
												</li>
											<?php } ?>
										</ul>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					
					<div id="aps-tb-filters" class="aps-tab-content">
						<?php // get aps filters
						$filters = get_aps_filters();
						
						if (is_array($filters)) { ?>
							<ul class="aps-filters-list">
								<?php foreach ($filters as $filter) { ?>
									<li class="aps-field-box">
										<div class="aps-inside">
											<h3 class="field-title"><?php echo $filter['name']; ?></h3>
											<?php $filter_slug = 'aps-' .$filter['slug'];
											// get filter terms
											$post_terms = get_the_terms( $post->ID, $filter_slug );
											$filter_terms = get_terms($filter_slug, 'hide_empty=0&orderby=id');
											
											// make an array of post terms
											$term_ids = array();
											if ($post_terms && !is_wp_error($post_terms)) {
												foreach ($post_terms as $term) {
													$term_ids[] = $term->term_id;
												}
											}
											
											// print all terms fields
											foreach ($filter_terms as $term) { ?>
												<label class="aps-cb-label"><?php echo $term->name; ?> <input type="checkbox" name="aps-filters[<?php echo $filter_slug; ?>][]" value="<?php echo $term->name; ?>"<?php if (in_array($term->term_id, $term_ids)) echo ' checked="checked"'; ?> /></label> 
											<?php } ?>
										</div>
									</li>
								<?php } ?>
							</ul>
						<?php } ?>
					</div>
					
					<div id="aps-tb-offers" class="aps-tab-content">
						<p>Add affiliate offers for this product, select a store enter title, price and your affiliate link.</p>
						<?php // get affiliate stores
						$stores = get_aps_affiliates();
						
						// get offers data
						$offers_data = get_aps_product_offers($post->ID); ?>
						<ul class="aps-sortable aps-fields-list aps-off-sorting"></ul>
						<div class="offer-hidden" style="display:none;">
							<div class="aps-box-inside">
								<span class="tb-title"><span class="dashicons dashicons-menu"></span></span>
								<div class="aps-col-2">
									<label><?php _e('Store', 'aps-text'); ?></label>
									<select class="aps-select-box offer-store" name="aps-dynamic">
										<?php if (!empty($stores)) {
											foreach ($stores as $store_key => $store) { ?>
												<option value="<?php echo $store_key; ?>"><?php echo $store['name']; ?></option>
											<?php }
										} ?>
									</select>
								</div>
								<div class="aps-col-4">
									<label><?php _e('Offer Title', 'aps-text'); ?></label>
									<input type="text" class="aps-text-input offer-title" name="aps-dynamic" value="" />
								</div>
								<div class="aps-col-2">
									<label><?php _e('Offer Price', 'aps-text'); ?></label>
									<input type="text" class="aps-text-input offer-price" name="aps-dynamic" value="" />
								</div>
								<div class="aps-col-4">
									<label><?php _e('Offer URL', 'aps-text'); ?></label>
									<input type="text" class="aps-text-input offer-url" name="aps-dynamic" value="" />
								</div>
								<a class="delete-offer aps-btn-del" href="#"><span class="dashicons dashicons-dismiss"></span></a>
							</div>
						</div>
						<div class="aps-offers">
							<a href="#" class="aps-btn aps-btn-green add-offer"><i class="aps-icon-plus"></i> <?php _e('Add Offer', 'aps-text'); ?></a>
						</div>
					</div>
					
					<div id="aps-tb-tabs" class="aps-tab-content">
						<?php // get aps tabs
						$tabs = get_aps_tabs();
						$tab1_display = $tabs['custom1']['display'];
						$tab2_display = $tabs['custom2']['display'];
						$tab3_display = $tabs['custom3']['display'];
						
						// get tabs meta data
						$tabs_data = get_aps_product_tabs($post->ID); ?>
						<p>Add content in editor(s) below to display in custom tab(s) (product single view).</p>
						<?php if (!$tab1_display && !$tab2_display && !$tab3_display) { ?>
							<p>Please setup tabs from tabs manager in plugin's settings page.</p>
						<?php }
						if ($tab1_display == 'yes') { ?>
							<div class="aps-editor">
								<label><?php echo __('Custom Tab', 'aps-text') .': ' .$tabs['custom1']['name']; ?></label><br />
								<?php wp_editor( $tabs_data['tab1'], 'customtabs1' ); ?>
							</div>
							<?php }
							if ($tab2_display == 'yes') { ?>
							<div class="aps-editor">
								<label><?php echo __('Custom Tab', 'aps-text') .': ' . $tabs['custom2']['name']; ?></label><br />
								<?php wp_editor( $tabs_data['tab2'], 'customtabs2' ); ?>
							</div>
							<?php }
							if ($tab3_display == 'yes') { ?>
							<div class="aps-editor">
								<label><?php echo __('Custom Tab', 'aps-text') .': ' . $tabs['custom3']['name']; ?></label><br />
								<?php wp_editor( $tabs_data['tab3'], 'customtabs3' ); ?>
							</div>
						<?php } ?>
					</div>
					
				</div>
			</div>
			<script type="text/javascript">
			(function($) {
				gallery_data = <?php echo json_encode($gallery_data); ?>;
				
				function aps_get_thumb(id, elem) {
					$.ajax({
						url: ajaxurl,
						type: "POST",
						data: {action: "aps-thumb", thumb: id},
						dataType: "json",
						success: function(res) {
							if (res.url != false) {
								elem.attr("src", res.url);
							}
						}
					});
				}
				
				if (typeof(gallery_data) != 'undefined') {
					var gallery_html = $(".field-hidden").html(), counter = 0;
					
					function aps_gallery_html(selection, db_val) {
						$(selection).find(":input").each(function() {
							$(this).val(db_val).attr('name', 'aps-gallery['+counter+']');
							if (db_val) {
								elem = $(this).parent().find("img");
								aps_get_thumb(db_val, elem);
							}
							counter++;
						});
					}
					
					$(document).on("click", "a.delete-field", function(e) {
						$(this).parents(".aps-image-box").remove();
						if (!$(".aps-image-box").length) counter = 0;
						e.preventDefault();
					});
					
					$(document).on("click", "a.add-field", function(e) {
						$("a.add-field").before('<div class="aps-image-box image-'+counter+'">'+gallery_html+'</div>');
						aps_gallery_html(".image-"+counter, "");
						e.preventDefault();
					});
					
					$.each(gallery_data, function(k, v) {
						$("a.add-field").before('<div class="aps-image-box image-'+counter+'">'+gallery_html+'</div>');
						aps_gallery_html(".image-"+counter, v);
					});
				}
				
				// use WordPress media uploader
				$(document).on("click", "a.add-image", function(e) {
					input = $(this).parent().find('input');
					frame = wp.media({
						title : "<?php _e('Select Gallery Image', 'aps-text'); ?>",
						multiple: false,
						library : { type : "image"},
						button : { text : "<?php _e('Add Image', 'aps-text'); ?>" },
					});
					frame.on("select", function() {
						selection = frame.state().get("selection");
						selection.each(function(image) {
							image_id = image.attributes.id;
							input.val(image_id);
							elem = input.parent().find("img");
							aps_get_thumb(image_id, elem);
						});
					});
					frame.open();
					e.preventDefault();
				});
				
				// aps videos
				videos_data = <?php echo json_encode($videos_data); ?>;
				
				if (typeof(videos_data) != 'undefined') {
					var video_html = $(".video-hidden").html(), counter = 0;
					
					function aps_videos_html(selection, vd_val) {
						$(selection).find(":input").each(function() {
							var thisi = $(this);
							if (thisi.hasClass("video-host")) {
								thisi.val(vd_val.host).attr('name', 'aps-videos['+counter+'][host]');
							} else if (thisi.hasClass("video-id")) {
								thisi.val(vd_val.vid).attr('name', 'aps-videos['+counter+'][vid]');
							}
						});
						counter++;
					}
					
					$(document).on("click", "a.delete-video", function(e) {
						$(this).parents(".video-box").remove();
						if (!$(".video-box").length) counter = 0;
						e.preventDefault();
					});
					
					$(document).on("click", "a.add-video", function(e) {
						$(".aps-vid-sorting").append('<li class="aps-field-box video-box video-'+counter+'">'+video_html+'</li>');
						var vd_val = {host: "youtube", vid: ""};
						aps_videos_html(".video-"+counter, vd_val);
						e.preventDefault();
					});
					
					$.each(videos_data, function(k, v) {
						$(".aps-vid-sorting").append('<li class="aps-field-box video-box video-'+counter+'">'+video_html+'</li>');
						aps_videos_html(".video-"+counter, v);
					});
				}
				
				// aps offers
				offers_data = <?php echo json_encode($offers_data); ?>;
				
				if (typeof(offers_data) != 'undefined') {
					var offer_html = $(".offer-hidden").html(), counter = 0;
					
					function aps_offers_html(selection, of_val) {
						$(selection).find(":input").each(function() {
							var thisi = $(this);
							if (thisi.hasClass("offer-store")) {
								thisi.val(of_val.store).attr('name', 'aps-offers['+counter+'][store]');
							} else if (thisi.hasClass("offer-title")) {
								thisi.val(of_val.title).attr('name', 'aps-offers['+counter+'][title]');
							} else if (thisi.hasClass("offer-price")) {
								thisi.val(of_val.price).attr('name', 'aps-offers['+counter+'][price]');
							} else if (thisi.hasClass("offer-url")) {
								thisi.val(of_val.url).attr('name', 'aps-offers['+counter+'][url]');
							}
						});
						counter++;
					}
					
					$(document).on("click", "a.delete-offer", function(e) {
						$(this).parents(".offer-box").remove();
						if (!$(".offer-box").length) counter = 0;
						e.preventDefault();
					});
					
					$(document).on("click", "a.add-offer", function(e) {
						$(".aps-off-sorting").append('<li class="aps-field-box offer-box offer-'+counter+'">'+offer_html+'</li>');
						var of_val = {store: "", title: "", price: "", url: ""};
						aps_offers_html(".offer-"+counter, of_val);
						e.preventDefault();
					});
					
					$.each(offers_data, function(k, v) {
						$(".aps-off-sorting").append('<li class="aps-field-box offer-box offer-'+counter+'">'+offer_html+'</li>');
						aps_offers_html(".offer-"+counter, v);
					});
				}
				
				// aps rating bars
				$("[data-slider]").each(function() {
					$(this).after('<span class="aps-range-output"></span>');
				}).bind("slider:ready slider:changed", function(event, data) {
					$(this).nextAll(".aps-range-output:first").html(data.value.toFixed(0));
					var totalSum = 0, inputs = 0;
					$("[data-slider]").each(function() {
						totalSum += Number($(this).val());
						inputs++
					});
					totalRating = totalSum / inputs;
					$(".aps-total-score").html(totalRating);
				});
				
				// data tabs
				$(".aps-tab-content:first").show();
				$("ul.aps-data-tabs li").click(function(e) {
					$("ul.aps-data-tabs li").removeClass("active");
					$(this).addClass("active");
					$(".aps-tab-content").hide();
					var activeTab = $(this).data("tab");
					$(activeTab).fadeIn("fast");
				});
			})(jQuery);
			
			jQuery(document).ready(function($) {
				$(".aps-gallery").sortable({
					items: ".image-box",
					opacity: 0.7
				});
				
				$(".aps-sortable").sortable({
					items: "li",
					opacity: 0.7
				});
				
				// groups tabs
				$(".aps-pil-content:first").show();
				$("ul.aps-data-pils li").click(function(e) {
					$("ul.aps-data-pils li").removeClass("active");
					$(this).addClass("active");
					$(".aps-pil-content").hide();
					var activeTab = $(this).data("pil");
					$(activeTab).fadeIn("fast");
				});
				
				// month year picker
				$(".aps-date-input").datepicker({dateFormat:"dd-mm-yy"});
			});
			</script>
		</div><?php
	}
	
	// Process the aps-product metabox data
	add_action( 'save_post', 'save_aps_product_metadata' );
	
	// save aps-product metadata
	function save_aps_product_metadata( $post_id ) {
		global $post;
		
		$post_type = (isset($_POST['post_type'])) ? $_POST['post_type'] : null;
		
		// check if post type == aps-products
		if ($post_type == 'aps-products') {
			
			$post_id = $_POST['post_ID'];
			$nonce = $_POST['aps_product_meta_nonce'];
			
			// verify nonce
			if ( !current_user_can('edit_post', $post_id) || !wp_verify_nonce($nonce, basename(__FILE__)) ) {
				return $post_id;
			}
			
			// get features data from input fields
			$features = (isset($_POST['aps-features'])) ? $_POST['aps-features'] : array();
			
			if (is_array($features)) {
				$features_data = array();
				foreach ($features as $key => $val) {	
					$features_data[$key] = trim($val);
				}
			}
			// save data in post meta fields
			update_post_meta( $post_id, 'aps-product-features', $features_data );
			
			// get gallery data from input fields
			$gallery = (isset($_POST['aps-gallery'])) ? $_POST['aps-gallery'] : array();
			
			if (is_array($gallery)) {
				$gallery_data = array();
				foreach ($gallery as $image => $id) {	
					$gallery_data[] = trim($id);
				}
			}
			// save data in post meta fields
			update_post_meta( $post_id, 'aps-product-gallery', $gallery_data );
			
			// get videos data from input fields
			$videos = (isset($_POST['aps-videos'])) ? $_POST['aps-videos'] : array();
			
			if (is_array($videos)) {
				$videos_data = array();
				foreach ($videos as $video) {	
					$videos_data[] = array(
						'host' => $video['host'],
						'vid' => $video['vid']
					);
				}
			}
			// save data in post meta fields
			update_post_meta( $post_id, 'aps-product-videos', $videos_data );
			
			// get aps attributes data from input fields
			$aps_attr = (isset($_POST['aps-attr'])) ? $_POST['aps-attr'] : array();
			
			if (is_array($aps_attr)) {
				foreach ($aps_attr as $key => $group) {
					$attr_data = array();
					foreach ($group as $attr => $value) {
						$attr_data[$attr] = trim(stripslashes($value));
					}
					
					// save data in post meta fields
					update_post_meta( $post_id, 'aps-attr-' .$key, $attr_data );
				}
			}
			
			// get filters data from input fields
			$filters = (isset($_POST['aps-filters'])) ? $_POST['aps-filters'] : array();
			
			if (is_array($filters)) {
				foreach ($filters as $filter => $terms) {	
					wp_set_post_terms($post_id, $terms, $filter);
				}
			}
			
			// get offers data from input fields
			$offers = (isset($_POST['aps-offers'])) ? $_POST['aps-offers'] : array();
			
			if (is_array($offers)) {
				$offers_data = array();
				foreach ($offers as $offer) {	
					$offers_data[] = array(
						'store' => trim($offer['store']),
						'title' => trim($offer['title']),
						'price' => trim($offer['price']),
						'url' => trim($offer['url']),
					);
				}
			}
			// save data in post meta fields
			update_post_meta( $post_id, 'aps-product-offers', $offers_data );
			
			// get ratings data from input fields
			$ratings = (isset($_POST['aps-rating'])) ? $_POST['aps-rating'] : array();
			
			$rating_data = array();
			$rating_total = 0;
			$total = 0;
			$count = 0;
			if (is_array($ratings)) {
				foreach ($ratings as $key => $val) {
					$rating_data[$key] = $val;
					$total += $val;
					$count++;
				}
				$rating_total = $total / $count;
			}
			// save data in post meta field
			update_post_meta( $post_id, 'aps-product-rating', $rating_data );
			update_post_meta( $post_id, 'aps-product-rating-total', $rating_total );
			
			// get custom tabs data from inputs
			$td1 = (isset($_POST['customtabs1'])) ? $_POST['customtabs1'] : null;
			$td2 = (isset($_POST['customtabs2'])) ? $_POST['customtabs2'] : null;
			$td3 = (isset($_POST['customtabs3'])) ? $_POST['customtabs3'] : null;
			
			$tabs = array();
			if ($td1) { $tabs['tab1'] = $td1; }
			if ($td2) { $tabs['tab2'] = $td2; }
			if ($td3) { $tabs['tab3'] = $td3; }
			
			// save data in post meta fields
			update_post_meta( $post_id, 'aps-custom-tabs', $tabs );
		}
	}
	
	// add action to print aps_product styles in post editing
	add_action( 'admin_print_styles-post-new.php', 'add_aps_product_styles', 11 );
	add_action( 'admin_print_styles-post.php', 'add_aps_product_styles', 11 );	
	
	// enqueue aps_product backend styles
	function add_aps_product_styles() {
		global $post;
		
		if ( $post->post_type == 'aps-products' ) {
			
			// enqueue APS plugin custom css
			wp_enqueue_style( 'aps-admin-style', APS_URL .'css/aps-admin.css' );
			// enqueue APS ui custom css
			wp_enqueue_style( 'aps-ui-style', APS_URL .'css/jquery-ui-custom.css' );
		}
	}
	
	// add action to print aps_product scripts in post editing
	add_action( 'admin_print_scripts-post-new.php', 'add_aps_product_scripts', 11 );
	add_action( 'admin_print_scripts-post.php', 'add_aps_product_scripts', 11 );	
	
	// enqueue aps_product backend scripts
	function add_aps_product_scripts() {
		global $post;
		
		if ( $post->post_type == 'aps-products' ) {
			
			// enqueue APS range slider script
			wp_enqueue_script( 'aps-slider', APS_URL .'js/simple-slider.min.js', array('jquery'), APS_VER );
			// enqueue datepicker js script
			wp_enqueue_script( 'jquery-ui-datepicker' );
		}
	}
	
	// add action register our post type aps-comparisons
	add_action( 'init', 'register_cpt_aps_comparisons' );
	
	// Register our Custom Post type as aps-comparisons
	function register_cpt_aps_comparisons() {
		$settings = get_aps_settings();
		$slug = (isset($settings['compare-slug'])) ? $settings['compare-slug'] : 'comparison';
		
		// labels text for our post type aps-comparisons
		$labels = array(
			// post type general name
			'name' => __( 'Comparisons', 'aps-text' ),
			// post type singular name
			'singular_name' => __( 'Comparison', 'aps-text' ),
			'name_admin_bar' => __( 'APS Comparison', 'aps-text' ),
			'menu_name' => __( 'APS Comparisons', 'aps-text' ),
			'add_new' => __( 'Add New APS Comparison', 'aps-text' ),
			'add_new_item' => __( 'Add New APS Comparison', 'aps-text' ),
			'edit_item' => __( 'Edit APS Comparison', 'aps-text' ),
			'new_item' => __( 'New APS Comparison', 'aps-text' ),
			'view_item' => __( 'View APS Comparison', 'aps-text' ),
			'search_items' => __( 'Search APS Comparisons', 'aps-text' ),
			'not_found' =>  __( 'No APS Comparisons found', 'aps-text' ),
			'not_found_in_trash' => __( 'No APS Comparisons found in Trash', 'aps-text' )
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'show_in_nav_menus' => false,
			'capability_type' => 'post',
			'hierarchical' => false,
			'taxonomies' => array('post_tag'),
			'has_archive' => true,
			'show_in_menu' => 'edit.php?post_type=aps-products',
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'rewrite' => array('slug' => $slug, 'with_front' => false)
		);	
		register_post_type( 'aps-comparisons', $args );
	}
	
	// aps-comparisons meta box hook into WordPress
	add_action( 'admin_init', 'add_aps_comparisons_metabox' );

	// Add meta boxs
	function add_aps_comparisons_metabox() {
		add_meta_box( 'aps_comparisons_meta_box', __( 'APS Products Comparison', 'aps-text' ), 'create_aps_products_comparisons', 'aps-comparisons', 'normal', 'core' );
	}

	// product images gallery meta box
	function create_aps_products_comparisons() {
		global $post;
		
		$comp_data = ($data = get_post_meta($post->ID, 'aps-product-comparison', true)) ? $data : array();
		$products = get_posts('post_type=aps-products&posts_per_page=-1&orderby=title&order=ASC');
		
		if ($products) {
			$list = array();
			foreach ($products as $post) {
				setup_postdata($post);
				$list[] = array(
					'id' => $post->ID,
					'title' => get_the_title()
				);
			}
		}
		// generate HTML for our meta box ?>
		<div class="admin-inside-box clearfix">
			<p><?php _e( 'Please add upto 3 products to compare', 'aps-text' ); ?></p>
			<input type="hidden" name="aps_products_compare_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>" />
			<div class="inside-box">
				<?php if ($list) { ?>
					<p>
						<label><?php _e('Product', 'aps-text' ); ?> 1</label>
						<select class="aps-compare-select" name="aps-compare[0]">
							<option value="">--- <?php _e('Select Product to add', 'aps-text' ); ?> ---</option>
							<?php foreach ($list as $product) { ?>
								<option value="<?php echo $product['id']; ?>"<?php if ($product['id'] == $comp_data[0]) echo ' selected="selected"'; ?>><?php echo $product['title']; ?></option>
							<?php } ?>
						</select>
					</p>
					<p>
						<label><?php _e('Product', 'aps-text' ); ?> 2</label>
						<select class="aps-compare-select" name="aps-compare[1]">
							<option value="">--- <?php _e('Select Product to add', 'aps-text' ); ?> ---</option>
							<?php foreach ($list as $product) { ?>
								<option value="<?php echo $product['id']; ?>"<?php if ($product['id'] == $comp_data[1]) echo ' selected="selected"'; ?>><?php echo $product['title']; ?></option>
							<?php } ?>
						</select>
					</p>
					<p>
						<label><?php _e('Product', 'aps-text' ); ?> 3</label>
						<select class="aps-compare-select" name="aps-compare[2]">
							<option value="">--- <?php _e('Select Product to add', 'aps-text' ); ?> ---</option>
							<?php foreach ($list as $product) { ?>
								<option value="<?php echo $product['id']; ?>"<?php if ($product['id'] == $comp_data[2]) echo ' selected="selected"'; ?>><?php echo $product['title']; ?></option>
							<?php } ?>
						</select>
					</p>
				<?php } ?>
			</div>
			<script type="text/javascript">
			(function($) {
				$(document).on("change", ".aps-compare-select", function() {
					var  delm = " <?php _e('vs', 'aps-text' ); ?> ",
					comp_title = $(".aps-compare-select").map(function() {
						if ($("option:selected", this).val() != "") {
							return $("option:selected", this).text();
						}
					}).get().join(delm);
					$("#title").focus().val(comp_title);
				});
			})(jQuery);
			</script>
		</div><?php
	}
	
	// Process the aps-comparisons metaboxs fields
	add_action( 'save_post', 'save_aps_products_comparison' );

	// save aps-comparisons meta values
	function save_aps_products_comparison( $post_id ) {
		global $post;
		
		$post_type = (isset($_POST['post_type'])) ? $_POST['post_type'] : null;
		
		// check if current user can edit post
		if ( $post_type == 'aps-comparisons' ) {
			
			$post_id = $_POST['post_ID'];
			// verify nonce
			if ( !wp_verify_nonce( $_POST['aps_products_compare_nonce'], basename(__FILE__) ) ) {
				return $post_id;
			}
			
			if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
			
			$compare = (isset($_POST['aps-compare'])) ? $_POST['aps-compare'] : array();
			
			if (is_array($compare)) {
				$data = array();
				foreach ($compare as $comp => $id) {	
					if (!empty($id)) {
						$data[] = trim($id);
					}
				}
				// save data in post meta fields
				update_post_meta( $post_id, 'aps-product-comparison', $data );
			}
		}
	}

	// custom taxonomy hook into the init action
	add_action( 'init', 'register_aps_filters', 0 );

	// register taxonomies as aps filters
	function register_aps_filters() {
		// get saved filters
		$filters = get_aps_filters();
		
		foreach ($filters as $filter) {
			// Add new taxonomy, NOT hierarchical (like tags)
			$name = $filter['name'] .' ';
			$slug = $filter['slug'];
			$term = __('Term', 'aps-text');
			$terms = __('Terms', 'aps-text');
			
			$labels = array(
				'name' => $name .$terms,
				'singular_name' => $name .$term,
				'search_items' => __('Search', 'aps-text') .' ' .$name .$terms,
				'popular_items' => __('Popular', 'aps-text') .' ' .$name .$terms,
				'all_items' => __('All', 'aps-text') .' ' .$name .$term,
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __('Edit', 'aps-text') .' ' .$name .$term,
				'update_item' => __('Update', 'aps-text') .' ' .$name .$term,
				'add_new_item' => __('Add New', 'aps-text') .' ' .$name .$term,
				'new_item_name' => __('New', 'aps-text') .' ' .$name .__('Term Name', 'aps-text'),
				'separate_items_with_commas' => __('Separate', 'aps-text') .' ' .$name .__('Terms with commas', 'aps-text'),
				'add_or_remove_items' => __('Add or remove', 'aps-text') .' ' .$name .$term,
				'choose_from_most_used' => __('Choose from the most used', 'aps-text') .' ' .$name .$terms,
				'not_found' => __('No', 'aps-text') .' ' .$name .__('Terms found', 'aps-text'),
				'menu_name' => 'APS ' .$name
			);
			
			$args = array(
				'labels' => $labels,
				'public' => false,
				'show_ui' => false,
				'show_in_nav_menus' => false,
				'show_admin_column' => false,
				'show_tagcloud' => false,
				'query_var' => false,
				'has_archive' => false,
				'rewrite' => false
			);
			
			register_taxonomy( 'aps-' .$slug, 'aps-products', $args );
		}
	}