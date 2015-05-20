<?php if (!defined('APS_VER')) exit('restricted access');
/*
 * @package WordPress
 * @subpackage APS Products
*/
	// create function Products List
	function aps_products_list_shortcode($atts) {
		extract(shortcode_atts( array(
			'num' => 12,
			'order' => 'DESC',
			'brand' => '',
			'type' => 'grid'
		), $atts ) );
		
		// query params
		$args = array(
			'post_type' => 'aps-products',
			'posts_per_page' => $num,
			'order' => $order
		);
		
		if (!empty($brand)) {
			$args['aps-brands'] = $brand;
		}
		
		// query products
		$products = new WP_Query($args);
		
		if ( $products->have_posts() ) :
			global $post;
			$out = '<ul class="aps-products aps-row clearfix ' .(($type == 'grid') ? 'aps-products-grid' : 'aps-products-list') .'">';
			while ( $products->have_posts() ) :
				$products->the_post();
				$out .= '<li><div class="aps-product-box">';
				
				// get product thumbnail
				$thumb = get_product_image(400, 400);
				
				// get main features attributes
				$main_features = get_aps_features();
				
				$features = get_aps_product_features($post->ID);
				$rating = get_product_rating_total($post->ID);
				$title = get_the_title();
				
				$out .= '<span class="aps-product-thumb"><img src="' .$thumb['url'] .'" width="400" height="400" alt="' .$title .'" /></span>';
				$out .= '<h2 class="aps-product-title"><a href="' .get_permalink() .'" title="' .$title .'">' .$title .'</a></h2>';
				$out .= '<span class="aps-view-info aps-icon-info"></span><div class="aps-product-details">';
				if (!empty($features)) {
					$out .= '<ul>';
					foreach ($main_features as $feature_key => $feature) {
						$out .= '<li><strong>' .$feature['name'] .':</strong> ' .$features[$feature_key] .'</li>';
					}
					$out .= '<li class="aps-specs-link"><a href="' .get_permalink() .'">' .__('View Details', 'aps-text') .' &rarr;</a></li>';
					$out .= '</ul>';
				}
				$out .= '<span class="aps-comp-rating">' .$rating .'</span>';
				$out .= '</div></div><div class="aps-buttons-box">';
				$out .= '<a class="aps-btn-boxed aps-add-compare" href="#" data-pid="' .$post->ID .'" data-msg="<strong>' .__('Success', 'aps-text') .':</strong> ' .__('You have added', 'aps-text') .' <strong>' .$title .'</strong> ' .__('to your', 'aps-text') .' <a href=\'' .$comp_link .'\'>' .__('comparison list', 'aps-text') .'</a>" title="' .__('Add to Compare', 'aps-text') .'"><i class="aps-icon-compare"></i></a>';
				$out .= '<a class="aps-btn-boxed aps-add-cart" href="#" data-pid="' .$post->ID .'" title="' .__('Add to Cart', 'aps-text') .'"><i class="aps-icon-cart"></i></a>';
				$out .= '</div></li>';
			endwhile;
			$out .= '</ul>';
			return $out;
			wp_reset_postdata();
		endif;
	}
	
	// add shortcode for products list [aps_products]
	add_shortcode('aps_products', 'aps_products_list_shortcode');
	
	// create function Product features
	function aps_product_features_shortcode($atts) {
		extract(shortcode_atts( array(
			'id' => '',
			'style' => 'list'
		), $atts ) );
		
		if (!empty($id)) {
			// get main features attributes
			$main_features = get_aps_features();
			
			// get the features values from post meta
			$features = get_aps_product_features($id);
			
			if (!empty($features)) {
				if ($style == 'metro') {
					$out = '<ul class="aps-features aps-row clearfix">';
						foreach ($main_features as $feature_key => $feature) {
							$out .= '<li><div class="aps-flipper">';
							$out .= '<div class="flip-front"><span class="aps-flip-icon aps-icon-' .$feature['icon'] .'"></span></div>';
							$out .= '<div class="flip-back"><span class="aps-back-icon aps-icon-' .$feature['icon'] .'"></span><br />';
							$out .= '<strong>' .$feature['name'] .'</strong><br /><span>' .$features[$feature_key] .'</span></div></div></li>';
						}
					$out .= '</ul>';
				} else {
					$out = '<ul class="aps-features-list">';
					foreach ($main_features as $feature_key => $feature) {
						$out .= '<li><strong>' .$feature['name'] .'</strong> ' .$features[$feature_key] .'</li>';
					}
					$out .= '</ul>';
				}
				return $out;
			}
		}
	}

	// add shortcode for product features [aps_product_features]
	add_shortcode('aps_product_features', 'aps_product_features_shortcode');
	
	// create function Product specs
	function aps_product_specs_shortcode($atts) {
		extract(shortcode_atts( array(
			'id' => ''
		), $atts ) );
		
		if (!empty($id)) {
			// get attributes groups
			$groups = get_aps_groups();
			
			// get defined attributes
			$attributes = get_aps_attributes();
			
			if (!empty($groups)) {
				// start foreach loop
				$out = '';
				foreach ($groups as $key => $group) {
					if ($group['display'] == 'yes') {
						// get post meta data by key
						$data = get_aps_product_attributes($id, $key);
						
						// check if data is an array
						if (!empty($data)) {
							$out .= '<div class="aps-group"><h3 class="aps-group-title">' .$group['name'];
							if ($design['icons']  == '1') $out .= '<span class="alignright aps-icon-' .$group['icon'] .'"></span>';
							$out .= '</h3><ul class="aps-specs-list">';
							foreach ($attributes[$key] as $attr_key => $attr_val) {
								// get attribute data
								$value = $data[$attr_key];
								if (!empty($value)) {
									// check if value is date
									if ($attr_val['type'] == 'date') {
										$value = date('d F Y', strtotime($value));
									} elseif ($attr_val['type'] == 'check') {
										$value = ($value == 'Yes') ? '<i class="aps-icon-check"></i>' : '<i class="aps-icon-cancel aps-icon-cross"></i>';
									}
									$out .= '<li><strong class="aps-term' .(!empty($attr_val['info']) ? ' aps-tooltip' : '') .'">' .$attr_val['name'] .'</strong>';
									if (!empty($attr_val['info'])) $out .= '<span class="aps-tooltip-data">' .$attr_val['info'] .'</span>';
									$out .= '<div class="aps-attr-value"><span class="aps-1co">' .nl2br($value) .'</span></div></li>';
								}
							}
							$out .= '</ul></div>';
						}
					}
				} // end foreach loop
			} // end if
			return $out;
		}
	}

	// add shortcode for product specs [aps_product_specs]
	add_shortcode('aps_product_specs', 'aps_product_specs_shortcode');