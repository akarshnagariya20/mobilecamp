<?php if (!defined('APS_VER')) exit('restricted access');
/*
 * @package WordPress
 * @subpackage APS Products
*/
	// add APS styles and scripts
	add_action( 'wp_enqueue_scripts', 'aps_styles_scripts', 12 );

	function aps_styles_scripts() {
		// enqueue aps main styles
		$main_styles = (is_rtl()) ? 'css/aps-styles-rtl.css' : 'css/aps-styles.css';
		wp_enqueue_style( 'aps-styles', APS_URL .$main_styles, '', APS_VER );
		
		// include only in aps-products single view
		if (is_single() && get_post_type() == 'aps-products') {
			// get zoom settings
			$zoom = get_option('aps-zoom');
			// get gallery (lightbox) settings
			$lightbox = get_option('aps-gallery');
			
			if ($zoom['enable']) {
				wp_enqueue_script( 'elevatezoom', APS_URL .'js/elevatezoom-min.js', array('jquery'), APS_VER );
			}
			
			if ($lightbox['enable']) {
				wp_enqueue_style( 'nivo-lightbox', APS_URL .'css/nivo-lightbox.css', '', APS_VER, 'all' );
				wp_enqueue_script( 'nivo-lightbox', APS_URL .'js/nivo-lightbox.min.js', array('jquery'), APS_VER, true );
			}
			
			// enqueue simple range slider script
			wp_enqueue_script( 'simple-slider', APS_URL .'js/simple-slider.min.js', array('jquery'), APS_VER, true );
		}
		
		// print inline script
		echo '<script type="text/javascript">var ajaxurl = "' .admin_url('admin-ajax.php') .'";</script>' ."\r\n";
		wp_enqueue_script( 'aps-main-script', APS_URL .'js/aps-main-script.js', array('jquery'), APS_VER, true );
	}
	
	// register aps-sidebar
	add_action( 'widgets_init', 'aps_register_sidebar' );
	
	function aps_register_sidebar() {
		$args = array(
			'name' => __('APS Sidebar', 'aps-text'),
			'id' => 'aps-sidebar',
			'description' => __('Widgets in this sidebar are displayed in the sidebar of APS templates (archives, single, index, compare)', 'aps-text'),
			'before_title' => '<h3 class="aps-widget-title">',
			'after_title' => '</h3>',
			'before_widget' => '<div class="aps-widget">',
			'after_widget' => '</div>'
		);
		register_sidebar( $args );
	}
	
	// gallery thumbnail hook into wp ajax
	add_action('wp_ajax_aps-thumb', 'aps_get_gallery_thumbnail');
	
	function aps_get_gallery_thumbnail() {
		// send thumbnail url via ajax response
		$thumb = (isset($_POST['thumb'])) ? trim($_POST['thumb']) : null;
		$image = get_product_image(160, 160, '', $thumb);
		wp_send_json($image);
	}
	
	// aps settings callback hook into wp ajax
	add_action('wp_ajax_aps-plugin', 'save_aps_plugin_settings');
	add_action('wp_ajax_aps-reset', 'reset_aps_plugin_settings');
	
	// save plugin setting
	function save_aps_plugin_settings() {
		// save aps plugin settings via ajax call
		$section = (isset($_POST['aps-section'])) ? trim($_POST['aps-section']) : null;
		$nonce = (isset($_POST['aps-nonce'])) ? trim($_POST['aps-nonce']) : null;
		$settings = (isset($_POST['aps-settings'])) ? $_POST['aps-settings'] : null;
		
		if ($section && wp_verify_nonce($nonce, 'aps_nonce')) {
			$data = array();
			if (is_array($settings)) {
				if ($section == 'aps-groups') {
					foreach ($settings as $key => $value) {
						$data[trim($key)] = array(
							'name' => trim(stripslashes($value['name'])),
							'icon' => trim($value['icon']),
							'display' => trim($value['display'])
						);
					}
					$groups = (isset($_POST['aps-groups'])) ? trim($_POST['aps-groups']) : null;
					update_option('aps-num-groups', $groups);
					
				} elseif ($section == 'aps-attributes') {
					foreach ($settings as $group_key => $group) {
						$group_data = array();
						foreach ($group as $key => $val) {
							$group_data[trim($key)] = array(
								'name' => trim(stripslashes($val['name'])),
								'type' => trim($val['type']),
								'display' => trim($val['display']),
								'info' => trim(stripslashes($val['info']))
							);
							
							if ($val['type'] == 'select' && isset($val['options'])) {
								$group_data[trim($key)]['options'] = $val['options'];
							}
						}
						$data[trim($group_key)] = $group_data;
					}
					
				} elseif ($section == 'aps-tabs') {
					foreach ($settings as $key => $value) {
						$data[trim($value['content'])] = array(
							'name' => trim(stripslashes($value['name'])),
							'content' => trim($value['content']),
							'display' => trim($value['display'])
						);
					}
					
				} elseif ($section == 'aps-features') {
					foreach ($settings as $key => $value) {
						$data[$key] = array(
							'name' => trim(stripslashes($value['name'])),
							'icon' => trim($value['icon'])
						);
					}
					
				} elseif ($section == 'aps-design') {
					foreach ($settings as $key => $value) {
						$data[$key] = trim(stripslashes($value));
					}
					// generate css styles
					aps_generate_styles($settings);
					
				} elseif ($section == 'aps-filters') {
					foreach ($settings as $value) {
						$filter_name = (!empty($value['name']) && $value['name'] != 'Filter Name') ? trim(stripslashes($value['name'])) : null;
						if ($filter_name) {
							$filter_slug = (!empty($value['slug'])) ? trim(stripslashes($value['slug'])) : $filter_name;
							$filter_slug = sanitize_title($filter_slug);
							$data[$filter_slug] = array(
								'name' => $filter_name,
								'slug' => $filter_slug
							);
						}
					}
					
				} elseif ($section == 'aps-rating-bars') {
					foreach ($settings as $value) {
						$bar_label = (!empty($value['label'])) ? trim(stripslashes($value['label'])) : null;
						if ($bar_label) {
							$bar_key = sanitize_title($bar_label);
							$data[$bar_key] = array(
								'label' => $bar_label,
								'value' => $value['value'],
								'info' => $value['info']
							);
						}
					}
					
				} elseif ($section == 'aps-affiliates') {
					foreach ($settings as $value) {
						$store_name = (!empty($value['name'])) ? trim(stripslashes($value['name'])) : null;
						if ($store_name) {
							$store_key = sanitize_title($store_name);
							$data[$store_key] = array(
								'name' => $store_name,
								'logo' => $value['logo']
							);
						}
					}
					
				} else {
					foreach ($settings as $key => $value) {
						$data[$key] = trim(stripslashes($value));
					}
					
				}
				
				// if section settings, flush rewrite rules
				if ($section == 'aps-settings') {
					aps_flush_rewrite_rules();
				}
			}
			
			update_option($section, $data);
			$success = true;
		}
		
		if ($success) {
			$msg = '<i class="aps-icon-check"></i> Your Changes saved successfully.';
		} else {
			$msg = '<i class="aps-icon-cancel"></i> Error: Your Changes not saved.';
		}
		
		// make an array of response
		$response = array(
			'success' => $success,
			'message' => $msg
		);
		
		wp_send_json($response);
	}
	
	// reset plugin setting
	function reset_aps_plugin_settings() {
		// reset aps plugin settings via ajax call
		$section = (isset($_POST['section'])) ? trim($_POST['section']) : null;
		$nonce = (isset($_POST['nonce'])) ? trim($_POST['nonce']) : null;
		$success = false;
		
		if (wp_verify_nonce($nonce, 'aps_nonce')) {
			if ($section == 'aps-design') {
				$success = aps_default_design_settings();
			} elseif ($section == 'aps-settings') {
				$success = aps_default_main_settings();
			} elseif ($section == 'aps-features') {
				$success = aps_default_features();
			} elseif ($section == 'aps-tabs') {
				$success = aps_default_tabs_settings();
			} elseif ($section == 'aps-gallery') {
				$success = aps_default_gallery_settings();
			} elseif ($section == 'aps-zoom') {
				$success = aps_default_zoom_settings();
			} elseif ($section == 'aps-affiliates') {
				$success = aps_default_affiliates();
			} elseif ($section == 'aps-groups') {
				$success = aps_default_groups();
			} elseif ($section == 'aps-attributes') {
				$success = aps_default_attributes();
			} elseif ($section == 'aps-rating-bars') {
				$success = aps_default_rating_bars();
			}
		}
		
		if ($success) {
			$msg = '<i class="aps-icon-check"></i> Default values are restored successfully.';
		} else {
			$msg = '<i class="aps-icon-cancel"></i> Error: Unable to restore default settings.';
		}
		
		// make an array of response
		$response = array(
			'success' => $success,
			'message' => $msg
		);
		wp_send_json($response);
	}
	
	// get saved settings from db
	function get_aps_settings() {
		return ($data = get_option('aps-settings')) ? $data : array();
	}

	// get saved design settings
	function get_aps_design() {
		return ($data = get_option('aps-design')) ? $data : array();
	}

	// get saved main features
	function get_aps_features() {
		return ($data = get_option('aps-features')) ? $data : array();
	}
	
	// get saved tabs from db
	function get_aps_tabs() {
		return ($data = get_option('aps-tabs')) ? $data : array();
	}
	
	// get saved groups from db
	function get_aps_groups() {
		return ($data = get_option('aps-groups')) ? $data : array();
	}
	
	// get saved attributes from db
	function get_aps_attributes() {
		return ($data = get_option('aps-attributes')) ? $data : array();
	}
	
	// get saved filters from db
	function get_aps_filters() {
		return ($data = get_option('aps-filters')) ? $data : array();
	}
	
	// get saved filters from db
	function get_aps_rating_bars() {
		return ($data = get_option('aps-rating-bars')) ? $data : array();
	}
	
	// get saved affiliate settings from db
	function get_aps_affiliates() {
		return ($data = get_option('aps-affiliates')) ? $data : array();
	}
	
	// flush rewrite rules
	function aps_flush_rewrite_rules() {
		flush_rewrite_rules();
	}
	
	// get aps product main features
	function get_aps_product_features($post_id) {
		return ($data = get_post_meta($post_id, 'aps-product-features', true)) ? $data : array();
	}
	
	// get aps product gallery
	function get_aps_product_gallery($post_id) {
		return ($data = get_post_meta($post_id, 'aps-product-gallery', true)) ? $data : array();
	}
	
	// get aps product videos
	function get_aps_product_videos($post_id) {
		return ($data = get_post_meta($post_id, 'aps-product-videos', true)) ? $data : array();
	}
	
	// get aps product offers
	function get_aps_product_offers($post_id) {
		return ($data = get_post_meta($post_id, 'aps-product-offers', true)) ? $data : array();
	}
	
	// get aps product custom tabs
	function get_aps_product_tabs($post_id) {
		return ($data = get_post_meta($post_id, 'aps-custom-tabs', true)) ? $data : array();
	}
	
	// get aps product rating total
	function get_product_rating_total($post_id) {
		return ($data = get_post_meta($post_id, 'aps-product-rating-total', true)) ? $data : null;
	}
	
	// get aps product ratings
	function get_product_rating($post_id) {
		return ($data = get_post_meta($post_id, 'aps-product-rating', true)) ? $data : array();
	}
	
	// get aps product ratings
	function get_aps_product_attributes($post_id, $group) {
		return ($data = get_post_meta($post_id, 'aps-attr-' .$group, true)) ? $data : array();
	}
	
	// ajax add review action
	add_action('wp_ajax_aps-review', 'aps_add_product_review');
	add_action('wp_ajax_nopriv_aps-review', 'aps_add_product_review');
	
	function aps_add_product_review() {
		
		$error = false;
		$success = false;
		$nonce = trim(strip_tags($_POST['nonce']));
		
		// verify nonce and continue.
		if (!wp_verify_nonce( $nonce, 'aps-review' )) {
			$error = __('There is something went wrong please try again', 'aps-text') .'<br />';
		}
		
		// okay, now we are safe to add a review
		$user = wp_get_current_user();
		
		if ($user->exists()) {
			$name = $user->display_name;
			$email = $user->user_email;
			$uid = $user->ID;
		} else {
			$name = (isset($_POST['aps-name'])) ? trim(strip_tags($_POST['aps-name'])) : null;
			$email = (isset($_POST['aps-email'])) ? trim(strip_tags($_POST['aps-email'])) : null;
			$uid = 0;
		}
		
		$pid = (isset($_POST['pid'])) ? trim(strip_tags($_POST['pid'])) : null;
		$title = (isset($_POST['aps-title'])) ? trim(strip_tags($_POST['aps-title'])) : null;
		$review = (isset($_POST['aps-review'])) ? trim(strip_tags(htmlspecialchars($_POST['aps-review'], ENT_QUOTES))) : null;
		$rating = (isset($_POST['rating'])) ? $_POST['rating'] : null;
		
		// validate review title
		if (strlen($title) < 10) {
			$error .= __('Please enter an informative title', 'aps-text') .'<br />';
		}
		
		// validate review text
		if (strlen($review) < 30) {
			$error .= __('Please enter a brief and informative review', 'aps-text');
		}
		
		if ($error == false) {
			// make an array of comment data
			$data = array(
				'comment_post_ID' => $pid,
				'comment_author' => $name,
				'comment_author_email' => $email,
				'comment_content' => $review,
				'comment_type' => 'review',
				'user_id' => $uid,
				'comment_approved' => 0,				
			);
			
			// add review (comment)
			$cid = wp_insert_comment($data);
			
			if ($cid) {
				// make an array of rating data
				$data = array();
				$total = 0;
				$count = 0;
				foreach ($rating as $r_key => $r_value) {
					$val = trim(strip_tags($r_value));
					$data[$r_key] = $val;
					$total += $val;
					$count++;
				}
				$data['total'] = $total / $count;
				
				// insert ratings data in comment meta
				update_comment_meta($cid, 'aps-review-rating', $data);
				update_comment_meta($cid, 'aps-review-title', $title);
				
				$success = __('Congratulations: Your review has been added and will be published soon.', 'aps-text');
			}
		}
		
		// make an array of response
		$response = array(
			'success' => $success,
			'error' => $error
		);
		wp_send_json($response);
	}
	
	// get reviews/comments for the post
	function aps_product_reviews($review, $args, $depth) {
		$GLOBALS['review'] = $review;
		$cid = $review->comment_ID;
		$title = get_comment_meta($cid, 'aps-review-title', true);
		$ratings = get_comment_meta($cid, 'aps-review-rating', true);
		$total_bar = $ratings['total'];
		$total_color = aps_rating_bar_color(round($total_bar)); ?>
		<li>
			<div class="aps-reviewer-image">
				<?php echo get_avatar($review->comment_author_email, 48, '', $review->comment_author); ?>
			</div>
			
			<div class="aps-review-meta">
				<strong><?php echo $review->comment_author; ?></strong><br />
				<span class="aps-review-date"><?php _e('Posted on', 'aps-text'); ?> <?php printf(__('%1$s at %2$s', 'aps-text'), get_comment_date(),  get_comment_time()) ?></span>
			</div>
			
			<div class="aps-review-rating mas-med-rating">
				<div class="aps-overall-rating" data-bar="true" data-rating="<?php echo $total_bar; ?>">
					<span class="aps-total-wrap">
						<span class="aps-total-bar <?php echo $total_color; ?>" data-type="bar"></span>
					</span>
					<span class="aps-rating-total" data-type="num"><?php echo $total_bar; ?></span>
				</div>
			</div>
			
			<h4 class="aps-review-title"><?php echo $title; ?></h4>
			<div class="aps-review-text">
				<?php comment_text(); ?>
			</div>
			
			<div class="aps-rating-panel">
				<ul class="aps-user-rating aps-row">
					<?php $count = count($ratings) - 1;
					foreach ($ratings as $rk => $rating) {
						if ($rk !== 'total') {
							$color = aps_rating_bar_color($rating); ?>
							<li>
								<div class="aps-rating-wip">
									<div class="aps-rating-cat">
										<strong><?php echo ucwords(str_replace('-', ' ', $rk)); ?></strong>
									</div>
									
									<div class="aps-rating-val">
										<span class="aps-rating-vic"><?php echo $rating; ?> / <?php echo $count; ?></span>
										<span class="aps-rating-bic <?php echo $color; ?>"></span>
									</div>
								</div>
							</li>
						<?php }
					} ?>
				</ul>
			</div>
		<?php
	}
	
	// aps rating bar color
	function aps_rating_bar_color($rating) {
		if ($rating <= 3) { $color = 'aps-red-bg'; }
		if ($rating > 3 && $rating <= 7) { $color = 'aps-orange-bg'; }
		if ($rating > 7 && $rating <= 9) { $color = 'aps-blue-bg'; }
		if ($rating == 10) { $color = 'aps-green-bg'; }
		return $color;
	}
	
	// get compare list from cookie
	function aps_get_compare_list() {
		global $post;
		if ($post->post_type == 'aps-comparisons') {
			$compList = ($data = get_post_meta($post->ID, 'aps-product-comparison', true)) ? $data : null;
		} else {
			$compList = isset($_COOKIE['aps_comp']) ? explode('-', $_COOKIE['aps_comp']) : null;
		}
		return $compList;
	}
	
	// show compare button if more than 1 device is added
	function aps_show_compare() {
		$compList = aps_get_compare_list();
		if (is_array($compList)) {
			$compare = count($compList);
			if ($compare > 1) {
				return true;
			}
		}
		return false;
	}
	
	// add comment type review into selectbox
	function aps_add_review_comment_type($type) {
		$type['review'] = __('Reviews', 'aps-text');
		return $type;
	}
	
	add_filter( 'admin_comment_types_dropdown', 'aps_add_review_comment_type' );
	
	// aps get compare page link
	function get_compare_page_link() {
		$settings = get_aps_settings();
		return get_permalink($settings['comp-page']);
	}
	
	// add filter search only in post titles (product name)
	function aps_products_search_where( $where, &$wp_query ) {
		global $wpdb;
		if ( $aps_title = $wp_query->get( 'aps_title' ) ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $aps_title ) ) . '%\'';
		}
		return $where;
	}
	add_filter( 'posts_where', 'aps_products_search_where', 10, 2 );
	
	// get product (post) brand (term) by post id
	function get_product_brand($post_id) {
		$terms = wp_get_post_terms($post_id, 'aps-brands');
		if (!is_wp_error($terms)) {
			return (isset($terms[0])) ? $terms[0] : false;
		}
	}
	
	// get all aps brands
	function get_all_aps_brands($sort='id') {
		$args = array(
			'hide_empty' => 0,
			'order' => 'ASC'
		);
		
		if ($sort == 'a-z') {
			$args['orderby'] = 'name'; 
		} elseif ($sort == 'z-a') {
			$args['orderby'] = 'name';
			$args['order'] = 'DESC';
		} elseif ($sort == 'count-l') {
			$args['orderby'] = 'count';
		} elseif ($sort == 'count-h') {
			$args['orderby'] = 'count';
			$args['order'] = 'DESC';
		} else {
			$args['orderby'] = 'id';
		}
		$terms = get_terms( 'aps-brands', $args );
		return $terms;
	}
	
	// clone APS post, Get the post type
	$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : null;
	
	if ($post_type == 'aps-products') {
		// add clone post link in post row
		add_filter( 'post_row_actions', 'aps_clone_product_row_link', 10, 2 );
		
		function aps_clone_product_row_link($actions, $post) {
			$post_id = $post->ID;
			
			if (!current_user_can( 'edit_post', $post_id ))
			return $post_id;
			
			// Create a nonce & add an action
			$nonce = wp_create_nonce( 'aps_clone_nonce' ); 
			$actions['clone_product'] = '<a class="clone-this" href="#" data-code="' .$nonce .'" data-id="' .$post_id .'">' .__('Clone Product', 'aps-text') .'</a>';
			
			return $actions;
		}
		
		// add action to admin_enqueue_script 
		add_action( 'admin_enqueue_scripts', 'aps_clone_product_script' );
		
		function aps_clone_product_script($hook_suffix) {
			if ( $hook_suffix == 'edit.php' ) {
				// enqueue post duplictor script
				wp_enqueue_script( 'aps-clone', APS_URL .'js/aps-clone.js', array('jquery'), APS_VER );
				echo '<style type="text/css">th#image {width:100px;}</style>';
			}
		}
	}
	
	// add clone post ajax action
	add_action( 'wp_ajax_clone_product', 'aps_clone_product_post' );
	
	function aps_clone_product_post() {
		$id = isset($_POST['id']) ? $_POST['id'] : null;
		$nonce = isset($_POST['code']) ? $_POST['code'] : null;
		
		if (!wp_verify_nonce( $nonce, 'aps_clone_nonce' )) die('Security check');
		
		$post = get_post($id);
		$brand = get_product_brand($id);
		
		$clone = array(
			'post_author' => $post->post_author,
			'post_status' => 'draft',
			'post_type' => $post->post_type,
			'post_title' => 'Cloned - ' .$post->post_title,
			'post_content' => $post->post_content
		);
		
		$cloned = wp_insert_post($clone);
		
		if ($cloned) {
			// get aps groups
			$groups = get_aps_groups();
			
			$group_keys = array();
			foreach ($groups as $group_key => $group) {
				$group_keys[] = 'aps-attr-' .$group_key;
			}
			
			// make an array of meta keys
			$meta_keys = array(
				'_thumbnail_id',
				'aps-product-gallery',
				'aps-product-rating',
				'aps-product-rating-total'
			);
			$meta_keys = array_merge($group_keys, $meta_keys);
			
			// get meta data from original post's meta and save for cloned post
			foreach ($meta_keys as $m_key) {
				// get post meta values from original post
				$data = get_post_meta( $id, $m_key, true );
				// update cloned post's meta data
				update_post_meta( $cloned, $m_key, $data );
			}
			
			// set brand (post term)
			wp_set_object_terms( $cloned, array($brand->slug), 'aps-brands' );
			echo true;
		}
		exit;
	}
	
	// get APS product image
	function get_product_image($width=300, $height=400, $pid=null, $imgid=null) {
		// get post thumbnail id
		if ($pid) {
			$thumb_id = get_post_thumbnail_id($pid);
		} elseif ($imgid) {
			$thumb_id = $imgid;
		} else {
			$thumb_id = get_post_thumbnail_id();
		}
		
		if ($thumb_id) {
			$thumb = vt_resize( $thumb_id, '', $width, $height, true );
		} else {
			$thumb = vt_resize( '', APS_URL .'img/product.jpg', $width, $height, true );
		}
		
		return $thumb;
	}
	
	// hook into aps_version_check
	add_action('aps_version_check', 'get_latest_aps_version_remote');
	
	// get latest version info from remote server
	function get_latest_aps_version_remote() {
		$site = get_home_url();
		$url = 'http://www.webstudio55.com/update/?item=aps&site=' .$site;
		$data = wp_remote_get( $url );
		$data = wp_remote_retrieve_body( $data );
		
		if ($data) {
			$update = json_decode($data, true);
			$version = $update['version'];
			$changes = $update['changelog'];
			$news = $update['news'];
			
			// save updates info in options
			update_option('aps-latest-version', $version);
			update_option('aps-latest-changes', $changes);
			update_option('aps-latest-news', $news);
		}
	}
	
	// check for updates after each hour
	add_action('wp', 'run_aps_updates_cron');
	
	function run_aps_updates_cron() {
		if (!wp_next_scheduled('aps_version_check')) {
			wp_schedule_event(time(), 'hourly', 'aps_version_check');
		}
	}
	
	// generate color scheme (skin) styles
	function aps_generate_styles($design) {
		
		// css styles files
		$css_custom = APS_DIR .'/css/pre/custom-styles.css';
		$css_custom_rtl = APS_DIR .'/css/pre/custom-styles-rtl.css';
		$common_css = APS_DIR .'/css/pre/styles-common.css';
		$common_css_rtl = APS_DIR .'/css/pre/styles-common-rtl.css';
		$css_1200 = APS_DIR .'/css/pre/styles-1200.css';
		$css_960 = APS_DIR .'/css/pre/styles-960.css';
		$res = APS_DIR .'/css/pre/responsive.css';
		$res_rtl = APS_DIR .'/css/pre/responsive-rtl.css';
		$res_1200 = APS_DIR .'/css/pre/responsive-1200.css';
		$new_file = APS_DIR .'/css/aps-styles.css';
		$new_file_rtl = APS_DIR .'/css/aps-styles-rtl.css';
		
		// check if use plugin styles
		if ($design['custom-css'] == '0') {
			
			// Open the files to get common styles
			$common_styles = file_get_contents($common_css);
			$common_styles_rtl = file_get_contents($common_css_rtl);
			
			// main container and boxes width
			if ($design['container'] == '1200') {
				// Open the file to get existing styles
				$styles_1200 = file_get_contents($css_1200);
				$styles = $common_styles .$styles_1200;
				$styles_rtl = $common_styles_rtl .$styles_1200;
				
				// include responsive css if enabled
				if ($design['responsive'] == '1') {
					$res_1200 = file_get_contents($res_1200);
					$styles .= file_get_contents($res) . $res_1200;
					$styles_rtl .= file_get_contents($res_rtl) .$res_1200;
				}
			} else {
				// Open the file to get existing styles
				$styles_960 = file_get_contents($css_960);
				$styles = $common_styles .$styles_960;
				$styles_rtl = $common_styles_rtl .$styles_960;
				
				// include responsive css if enabled
				if ($design['responsive'] == '1') {
					$styles .= file_get_contents($res);
					$styles_rtl .= file_get_contents($res_rtl);
				}
			}
			
			// switch skins
			switch ($design['skin']) {
				// blue skin
				case 'skin-blue':
					$color_1 = '#097def'; $color_2 = '#3199fe'; $color_3 = '#a7d3fe';
				break;
				
				// light blue skin
				case 'skin-light-blue':
					$color_1 = '#02a8de'; $color_2 = '#16baef'; $color_3 = '#a9e2f4';
				break;
				
				// green skin
				case 'skin-green':
					$color_1 = '#7cb82d'; $color_2 = '#8ac63c'; $color_3 = '#bee888';
				break;
				
				// sea green skin
				case 'skin-sea-green':
					$color_1 = '#10bfa4'; $color_2 = '#23cbb1'; $color_3 = '#8ce7d9';
				break;
				
				// orange skin
				case 'skin-orange':
					$color_1 = '#ec7306'; $color_2 = '#f38522'; $color_3 = '#fec490';
				break;
				
				// red skin
				case 'skin-red':
					$color_1 = '#d71717'; $color_2 = '#e72626'; $color_3 = '#f69999';
				break;
				
				// pink skin
				case 'skin-pink':
					$color_1 = '#ef0a7b'; $color_2 = '#fa228d'; $color_3 = '#ffb3d9';
				break;
				
				// purple skin
				case 'skin-purple':
					$color_1 = '#d60ad8'; $color_2 = '#e116e3'; $color_3 = '#f4a4f5';
				break;
				
				// brown skin
				case 'skin-brown':
					$color_1 = '#a55422'; $color_2 = '#b36230'; $color_3 = '#efc0a3';
				break;
				
				// custom skin
				case 'skin-custom':
					$color_1 = $design['color1']; $color_2 = $design['color2']; $color_3 = $design['color3'];
				break;
			}
			
			// append skin styles
			$skin_styles = "/* APS Skin CSS Styles */ \r\n";
			$skin_styles .= ".slider-aps > .highlight-track {background:$color_3;}\r\n";
			$skin_styles .= ".aps-view-info:hover {color:$color_1;}\r\n";
			$skin_styles .= ".flip-front, .aps-gallery-zoom, .aps-total-score, .aps-btn-skin, a.page-numbers, .aps-range-output, .slider-aps > .dragger, .aps-display-controls ul li a.selected, .aps-dropdown:hover .aps-current-dp, .aps-brands-list li a span, .aps-filter-cb:checked + .aps-cb-holder, .aps-search-btn:hover, .aps-pd-search:hover, .aps-btn-boxed:hover {background:$color_2;}\r\n";
			$skin_styles .= ".aps-brands-list li a.current {border-left:2px solid $color_2}\r\n";
			$skin_styles .= ".flip-back, .aps-btn-skin:hover, a.page-numbers:hover, .slider-aps > .dragger:hover {background:$color_1;}\r\n";
			$skin_styles .= ".aps-btn-skin, a.page-numbers, .aps-dropdown:hover .aps-current-dp, .aps-filter-cb:checked + .aps-cb-holder, .aps-search-btn:hover, .aps-pd-search:hover, .aps-btn-boxed:hover {border-color:$color_1;}\r\n";
			$skin_styles .= ".aps-product-box, .aps-product-pic img, .aps-gallery-thumbs li a, .aps-rd-box, .aps-specs-list, .aps-comps-thumb, .aps-wd-products li a, .aps-cp-thumb, .aps-video-box ";
			
			// border or box shadow
			if ($design['border'] == 'border') {
				$skin_styles .= "{border:1px solid #e8e9ea;}\r\n";
			} else {
				$skin_styles .= "{box-shadow:1px 1px 3px rgba(0,0,0, .12); -webkit-box-shadow:1px 1px 3px rgba(0,0,0, .12); -moz-box-shadow:1px 1px 3px rgba(0,0,0, .12); -ms-box-shadow:1px 1px 3px rgba(0,0,0, .12);}\r\n";
			}
			
			$styles .= $skin_styles;
			$styles_rtl .= $skin_styles;
		} else {
			// use custom styles
			$styles = file_get_contents($css_custom);
			$styles_rtl = file_get_contents($css_custom_rtl);
		}
		
		// write the CSS styles to the file
		file_put_contents($new_file, $styles);
		file_put_contents($new_file_rtl, $styles_rtl);
	}