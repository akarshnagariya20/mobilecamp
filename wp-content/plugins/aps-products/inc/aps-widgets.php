<?php if (!defined('APS_VER')) exit('restricted access');
/*
 * @package WordPress
 * @subpackage APS Products
*/
	// Register APS search widget
	function aps_search_widget_init() {
		register_widget( 'aps_search_widget' );
	}

	add_action( 'widgets_init', 'aps_search_widget_init' );

	// add aps ajax search acation into wp ajax
	add_action('wp_ajax_aps-search', 'aps_ajax_search_results');
	add_action('wp_ajax_nopriv_aps-search', 'aps_ajax_search_results');

	// ajax search results
	function aps_ajax_search_results() {
		$num = isset($_GET['num']) ? trim(strip_tags($_GET['num'])) : 3;
		$query = isset($_GET['search']) ? trim(strip_tags($_GET['search'])) : null;
		$type = isset($_GET['type']) ? trim(strip_tags($_GET['type'])) : null;
		
		$args = array(
			'post_type' => 'aps-products',
			'posts_per_page' => $num,
			'aps_title' => $query
		);
		
		$search = new WP_Query($args);
		$results = array();
		
		if ($search->have_posts()) :
			$count = 0;
			while ($search->have_posts()) :
				$search->the_post();
				
				$pid = get_the_ID();
				$link = get_permalink();
				$title = get_the_title();
				$thumb = ($type == 'compare') ? get_product_image(120, 120) : get_product_image(80, 80);
				$brand = get_product_brand($pid);
				$rating = get_product_rating_total($pid);
				$msg = $title .' ' .__('successfully added to compare, reloading now...', 'aps-text');
				
				if ($type == 'compare') {
					$result = '<li><a class="aps-add-compare" href="#" data-pid="' .$pid .'" title="' .__('Click to Add', 'aps-text') .'" data-msg="' .$msg .'">';
					$result .= '<span class="aps-wd-thumb"><img src="' .$thumb['url'] .'" alt="' .$title .'" /></span>';
					$result .= '<span class="aps-wd-title">' .$title .'</span></a></li>';
				} else {
					$result = '<li><span class="aps-res-thumb"><img src="' .$thumb['url'] .'" /></span>';
					$result .= '<a class="aps-res-title" href="' .$link .'">' .$title .'</a><br />';
					$result .= '<span class="aps-res-brand">' .__('Brand', 'aps-text') .': <a href="' .get_term_link($brand) .'"><strong>' .$brand->name .'</strong></a></span><br />';
					$result .= '<span class="aps-res-rating">' .__('Rating', 'aps-text') .': <strong>' .$rating .'</strong></span><br />';
					$result .= '<span class="aps-res-view"><a href="' .$link .'">' .__('View Specs', 'aps-text') .' &rarr;</a></span></li>';
					// counter
					$count++;
				}
				
				// save results data into array
				$results[$pid] = $result;
			endwhile;
			
			if (!$type && $count >= $num) {
				// add view more link in the end of array
				$results['more'] = '<li><a class="aps-res-more" href="' .home_url() .'?post_type=aps-products&s=' .$query .'">' .__('Veiw All Results', 'aps-text') .'</a></li>';
			} ?>
			<?php
		else:
			// nothing matched
			$results['not'] = '<li>' .__('No Product Found for your query', 'aps-text') .'</li>';
		endif;
		
		// reset query data
		wp_reset_postdata();
		
		wp_send_json($results);
	}

	class aps_search_widget extends WP_Widget {

		function aps_search_widget() {
			
			// Widget settings
			$widget_ops = array( 'classname' => 'aps_search', 'description' => __( 'APS Live Search (ajax powered instant search widget)', 'aps-text' ) );
			
			// Widget control settings
			$control_ops = array( 'width' => 220, 'height' => 220, 'id_base' => 'aps_search' );
			
			// Create the widget
			$this->WP_Widget( 'aps_search', __( 'APS Live Search', 'aps-text' ), $widget_ops, $control_ops );
		}

		// display the widget on the screen
		function widget( $args, $instance ) {
			extract( $args );
			
			// saved variables from the widget settings
			$title = apply_filters('widget_title', $instance['title'] );
			$num = (int) $instance['results'];
			
			// Before widget
			echo $before_widget ."\n";
			
			// Display the widget title if one was input
			if ( $title )
			echo $before_title . $title . $after_title ."\n"; ?>
			
			<form class="aps-search-form" method="get" action="<?php echo home_url(); ?>">
				<div class="aps-search-field">
					<input type="hidden" name="post_type" value="aps-products" />
					<input type="text" name="s" class="aps-search" value="" />
					<span class="aps-icon-search aps-search-btn"></span>
				</div>
			</form>
			<script type="text/javascript">
			(function($) {
				$(".aps-search").each(function() {
					var sinput = $(this),
					sparent = sinput.parent(),
					oul = (!!sparent.find(".aps-ajax-results").length ? $(".aps-ajax-results") : $("<ul class='aps-ajax-results'></ul>"));
					sinput.on("input propertychange", function(e) {
						var query = sinput.val();
						if (query.length > 1) {
							$.getJSON(
								ajaxurl + "?action=aps-search&num=<?php echo $num; ?>&search=" + query,
								function(data) {
									if (data) {
										oul.empty();
										$.each(data, function(k, v) {
											oul.append(v)
										});
										oul.remove();
										sparent.append(oul);
									}
								}
							);
						} else {
							oul.empty();
						}
					}).blur(function() {
						setTimeout(function() {
							oul.hide()
						}, 500);
					}).focus(function() {
						oul.show();
					});
					
					// submit form on click
					$(".aps-search-btn").click(function() {
						sinput.parents(".aps-search-form").trigger("submit");
					});
				});
			})(jQuery);
			</script>
			<?php echo $after_widget ."\n";
		}

		// Update the widget settings.
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['results'] = (int) $new_instance['results'];
			return $instance;
		}

		/*
		* Displays the widget settings controls on the widget panel.
		* Make use of the get_field_id() and get_field_name() function
		* when creating your form elements. This handles the confusing stuff.
		*/
		
		function form( $instance ) {
			
			// Set up some default widget settings
			$defaults = array(
				'title' => __( 'Search', 'aps-text' ),
				'results' => 5
			);
			
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			
			<!-- Title input field -->
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'aps-text'); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			
			<!-- Show results Numbers input select -->
			<p>
				<label for="<?php echo $this->get_field_id( 'results' ); ?>"><?php _e( 'Show Results:', 'aps-text' ); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'results' ); ?>" name="<?php echo $this->get_field_name( 'results' ); ?>">
					<option value="2" <?php if ($instance['results'] == 2) echo 'selected="selected"'; ?>>2 <?php _e( 'Results', 'aps-text' ); ?></option>
					<option value="3" <?php if ($instance['results'] == 3) echo 'selected="selected"'; ?>>3 <?php _e( 'Results', 'aps-text' ); ?></option>
					<option value="4" <?php if ($instance['results'] == 4) echo 'selected="selected"'; ?>>4 <?php _e( 'Results', 'aps-text' ); ?></option>
					<option value="5" <?php if ($instance['results'] == 5) echo 'selected="selected"'; ?>>5 <?php _e( 'Results', 'aps-text' ); ?></option>
					<option value="6" <?php if ($instance['results'] == 6) echo 'selected="selected"'; ?>>6 <?php _e( 'Results', 'aps-text' ); ?></option>
					<option value="10" <?php if ($instance['results'] == 10) echo 'selected="selected"'; ?>>10 <?php _e( 'Results', 'aps-text' ); ?></option>
				</select>
			</p>
			<?php
		}
	}
	
	// Register new arrivals widget
	function aps_new_arrivals_widget_init() {
		register_widget( 'aps_new_arrivals_widget' );
	}

	add_action( 'widgets_init', 'aps_new_arrivals_widget_init' );

	class aps_new_arrivals_widget extends WP_Widget {

		function aps_new_arrivals_widget() {
			
			// Widget settings
			$widget_ops = array( 'classname' => 'aps_new_arrivals', 'description' => __( 'Display New Arrivals (APS Products)', 'aps-text' ) );
			
			// Widget control settings
			$control_ops = array( 'width' => 220, 'height' => 220, 'id_base' => 'aps_new_arrivals' );
			
			// Create the widget
			$this->WP_Widget( 'aps_new_arrivals', __( 'APS New Arrivals', 'aps-text' ), $widget_ops, $control_ops );
		}

		// display the widget on the screen
		function widget( $args, $instance ) {
			extract( $args );
			
			// saved variables from the widget settings
			$title = apply_filters('widget_title', $instance['title'] );
			$show_posts = (int) $instance['products'];
			
			// Before widget
			echo $before_widget ."\n";
			
			// Display the widget title if one was input
			if ( $title )
			echo $before_title . $title . $after_title ."\n";
			
			// Get Recent Posts
			global $post;
			$current = (isset($post->ID)) ? $post->ID : 0;
			$exclude = array( $current );
			
			$args = array(
				'post_type' => 'aps-products',
				'posts_per_page' => $show_posts,
				'post__not_in' => $exclude
			);
			
			$new_arrivals = new WP_Query($args); ?>
			
			<ul class="aps-wd-products aps-row-mini clearfix">
				<?php while ( $new_arrivals->have_posts() ) :
					$new_arrivals->the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<span class="aps-wd-thumb">
								<?php $thumb = get_product_image(120, 120); ?>
								<img src="<?php echo $thumb['url']; ?>" alt="<?php the_title_attribute(); ?>" />
							</span>
							<span class="aps-wd-title"><?php the_title(); ?></span>
						</a>
					</li>
				<?php endwhile;
				// reset query data
				wp_reset_postdata(); ?>
			</ul>
			<?php echo $after_widget ."\n";
		}

		// Update the widget settings.
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['products'] = (int) $new_instance['products'];
			return $instance;
		}

		/*
		* Displays the widget settings controls on the widget panel.
		* Make use of the get_field_id() and get_field_name() function
		* when creating your form elements. This handles the confusing stuff.
		*/
		
		function form( $instance ) {
			
			// Set up some default widget settings
			$defaults = array(
				'title' => __( 'New Arrivals', 'aps-text' ),
				'products' => 6
			);
			
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			
			<!-- Title input field -->
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'aps-text'); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			
			<!-- Show products Numbers input select -->
			<p>
				<label for="<?php echo $this->get_field_id( 'products' ); ?>"><?php _e( 'Show Products:', 'aps-text' ); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'products' ); ?>" name="<?php echo $this->get_field_name( 'products' ); ?>">
					<option value="3" <?php if ($instance['products'] == 3) echo 'selected="selected"'; ?>>3 <?php _e( 'Products', 'aps-text' ); ?></option>
					<option value="6" <?php if ($instance['products'] == 6) echo 'selected="selected"'; ?>>6 <?php _e( 'Products', 'aps-text' ); ?></option>
					<option value="9" <?php if ($instance['products'] == 9) echo 'selected="selected"'; ?>>9 <?php _e( 'Products', 'aps-text' ); ?></option>
					<option value="12" <?php if ($instance['products'] == 12) echo 'selected="selected"'; ?>>12 <?php _e( 'Products', 'aps-text' ); ?></option>
				</select>
			</p>
			<?php
		}
	}
	
	// Register Comparisons widget
	function aps_comparisons_widget_init() {
		register_widget( 'aps_comparisons_widget' );
	}

	add_action( 'widgets_init', 'aps_comparisons_widget_init' );

	class aps_comparisons_widget extends WP_Widget {

		function aps_comparisons_widget() {
			
			// Widget settings
			$widget_ops = array( 'classname' => 'aps_comparisons', 'description' => __( 'Display Comparisons List', 'aps-text' ) );
			
			// Widget control settings
			$control_ops = array( 'width' => 220, 'height' => 220, 'id_base' => 'aps_comparisons' );
			
			// Create the widget
			$this->WP_Widget( 'aps_comparisons', __( 'APS Comparisons', 'aps-text' ), $widget_ops, $control_ops );
		}

		// display the widget on the screen
		function widget( $args, $instance ) {
			extract( $args );
			
			// saved variables from the widget settings
			$title = apply_filters('widget_title', $instance['title'] );
			$show_posts = (int) $instance['number'];
			
			// Before widget
			echo $before_widget ."\n";
			
			// Display the widget title if one was input
			if ( $title )
			echo $before_title . $title . $after_title ."\n";
			
			// Get Recent Posts
			global $post;
			$current = (isset($post->ID)) ? $post->ID : 0;
			$exclude = array( $current );
			
			$args = array(
				'post_type' => 'aps-comparisons',
				'posts_per_page' => $show_posts,
				'post__not_in' => $exclude
			);
			
			$comparisons = new WP_Query($args); ?>
			
			<ul class="aps-wd-compares clearfix">
				<?php while ( $comparisons->have_posts() ) :
					$comparisons->the_post(); ?>
					<li>
						<span class="aps-cp-thumb">
							<?php $thumb = get_product_image(80, 50); ?>
							<img src="<?php echo $thumb['url']; ?>" alt="<?php the_title_attribute(); ?>" />
						</span>
						<span class="aps-cp-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></span>
						<a class="aps-cp-link" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php _e('View Comparison', 'aps-text'); ?> &rarr;</a>
					</li>
				<?php endwhile;
				// reset query data
				wp_reset_postdata(); ?>
			</ul>
			<?php echo $after_widget ."\n";
		}

		// Update the widget settings.
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = (int) $new_instance['number'];
			return $instance;
		}

		/*
		* Displays the widget settings controls on the widget panel.
		* Make use of the get_field_id() and get_field_name() function
		* when creating your form elements. This handles the confusing stuff.
		*/
		
		function form( $instance ) {
			
			// Set up some default widget settings
			$defaults = array(
				'title' => __( 'Recent Compares', 'aps-text' ),
				'number' => 3
			);
			
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			
			<!-- Title input field -->
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'aps-text'); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			
			<!-- Show number of compare input select -->
			<p>
				<label for="<?php echo $this->get_field_id( 'products' ); ?>"><?php _e( 'Show Compares:', 'aps-text' ); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'products' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>">
					<option value="1" <?php if ($instance['number'] == 1) echo 'selected="selected"'; ?>>1 <?php _e( 'Compares', 'aps-text' ); ?></option>
					<option value="2" <?php if ($instance['number'] == 2) echo 'selected="selected"'; ?>>2 <?php _e( 'Compares', 'aps-text' ); ?></option>
					<option value="3" <?php if ($instance['number'] == 3) echo 'selected="selected"'; ?>>3 <?php _e( 'Compares', 'aps-text' ); ?></option>
					<option value="4" <?php if ($instance['number'] == 4) echo 'selected="selected"'; ?>>4 <?php _e( 'Compares', 'aps-text' ); ?></option>
					<option value="5" <?php if ($instance['number'] == 5) echo 'selected="selected"'; ?>>5 <?php _e( 'Compares', 'aps-text' ); ?></option>
					<option value="6" <?php if ($instance['number'] == 6) echo 'selected="selected"'; ?>>6 <?php _e( 'Compares', 'aps-text' ); ?></option>
					<option value="8" <?php if ($instance['number'] == 8) echo 'selected="selected"'; ?>>8 <?php _e( 'Compares', 'aps-text' ); ?></option>
					<option value="10" <?php if ($instance['number'] == 10) echo 'selected="selected"'; ?>>10 <?php _e( 'Compares', 'aps-text' ); ?></option>
					<option value="12" <?php if ($instance['number'] == 12) echo 'selected="selected"'; ?>>12 <?php _e( 'Compares', 'aps-text' ); ?></option>
				</select>
			</p>
			<?php
		}
	}
	
	// Register top rated widget
	function aps_top_products_widget_init() {
		register_widget( 'aps_top_rated_products_widget' );
	}

	add_action( 'widgets_init', 'aps_top_products_widget_init' );

	class aps_top_rated_products_widget extends WP_Widget {

		function aps_top_rated_products_widget() {
			
			// Widget settings
			$widget_ops = array( 'classname' => 'aps_top_products', 'description' => __( 'Display Top Rated (APS Products)', 'aps-text' ) );
			
			// Widget control settings
			$control_ops = array( 'width' => 220, 'height' => 220, 'id_base' => 'aps_top_products' );
			
			// Create the widget
			$this->WP_Widget( 'aps_top_products', __( 'APS Top Rated Products', 'aps-text' ), $widget_ops, $control_ops );
		}

		// display the widget on the screen
		function widget( $args, $instance ) {
			extract( $args );
			
			// saved variables from the widget settings
			$title = apply_filters('widget_title', $instance['title'] );
			$show_posts = (int) $instance['products'];
			
			// Before widget
			echo $before_widget ."\n";
			
			// Display the widget title if one was input
			if ( $title )
			echo $before_title . $title . $after_title ."\n";
			
			// Get Recent Posts
			global $post;
			$current = (isset($post->ID)) ? $post->ID : 0;
			$exclude = array( $current );
			
			$args = array(
				'post_type' => 'aps-products',
				'posts_per_page' => $show_posts,
				'post__not_in' => $exclude,
				'meta_key' => 'aps-product-rating-total',
				'orderby' => 'meta_value_num',
				'meta_query' => array(
					array(
						'key' => 'aps-product-rating-total',
						'value' => array( 5, 10 ),
						'type' => 'numeric',
						'compare' => 'BETWEEN'
					)
				)
			);
			
			$top_products = new WP_Query($args); ?>
			
			<ul class="aps-wd-products aps-row-mini clearfix">
				<?php while ( $top_products->have_posts() ) :
					$top_products->the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<span class="aps-wd-thumb">
								<?php $thumb = get_product_image(120, 120); ?>
								<img src="<?php echo $thumb['url']; ?>" alt="<?php the_title_attribute(); ?>" />
							</span>
							<span class="aps-wd-title"><?php the_title(); ?></span>
						</a>
					</li>
				<?php endwhile;
				// reset query data
				wp_reset_postdata(); ?>
			</ul>
			<?php echo $after_widget ."\n";
		}

		// Update the widget settings.
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['products'] = (int) $new_instance['products'];
			return $instance;
		}

		/*
		* Displays the widget settings controls on the widget panel.
		* Make use of the get_field_id() and get_field_name() function
		* when creating your form elements. This handles the confusing stuff.
		*/
		
		function form( $instance ) {
			
			// Set up some default widget settings
			$defaults = array(
				'title' => __( 'Top Rated', 'aps-text' ),
				'products' => 6
			);
			
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			
			<!-- Title input field -->
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'aps-text'); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			
			<!-- Show products Numbers input select -->
			<p>
				<label for="<?php echo $this->get_field_id( 'products' ); ?>"><?php _e( 'Show Products:', 'aps-text' ); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'products' ); ?>" name="<?php echo $this->get_field_name( 'products' ); ?>">
					<option value="3" <?php if ($instance['products'] == 3) echo 'selected="selected"'; ?>>3 <?php _e( 'Products', 'aps-text' ); ?></option>
					<option value="6" <?php if ($instance['products'] == 6) echo 'selected="selected"'; ?>>6 <?php _e( 'Products', 'aps-text' ); ?></option>
					<option value="9" <?php if ($instance['products'] == 9) echo 'selected="selected"'; ?>>9 <?php _e( 'Products', 'aps-text' ); ?></option>
					<option value="12" <?php if ($instance['products'] == 12) echo 'selected="selected"'; ?>>12 <?php _e( 'Products', 'aps-text' ); ?></option>
				</select>
			</p>
			<?php
		}
	}
	
	// Register APS Brands widget
	function aps_brands_widget_init() {
		register_widget( 'aps_brands_widget' );
	}

	add_action( 'widgets_init', 'aps_brands_widget_init' );

	class aps_brands_widget extends WP_Widget {

		function aps_brands_widget() {
			
			// Widget settings
			$widget_ops = array( 'classname' => 'aps_brands', 'description' => __( 'Display Brands List', 'aps-text' ) );
			
			// Widget control settings
			$control_ops = array( 'width' => 220, 'height' => 220, 'id_base' => 'aps_brands' );
			
			// Create the widget
			$this->WP_Widget( 'aps_brands', __( 'APS Brands', 'aps-text' ), $widget_ops, $control_ops );
		}

		// display the widget on the screen
		function widget( $args, $instance ) {
			extract( $args );
			
			// saved variables from the widget settings
			$title = apply_filters('widget_title', $instance['title'] );
			$show_nums = $instance['nums'];
			
			// Before widget
			echo $before_widget ."\n";
			
			// Display the widget title if one was input
			if ( $title )
			echo $before_title . $title . $after_title ."\n";
			
			// Get all brands
			$brands = get_all_aps_brands();
			if ($brands) {
				$term = ($brand = get_query_var('aps-brands')) ? get_term_by('slug', $brand, 'aps-brands') : null; ?>
				<ul class="aps-brands-list">
					<?php foreach ($brands as $brand) { ?>
						<li>
							<a <?php if (isset($term->term_id) && $brand->term_id == $term->term_id) echo 'class="current" '; ?>href="<?php echo get_term_link($brand); ?>">
								<?php echo $brand->name; if ($show_nums == 'yes') echo ' <span>' .$brand->count .'</span>'; ?>
							</a>
						</li>
					<?php } ?>
				</ul>
				<?php
			}
			echo $after_widget ."\n";
		}

		// Update the widget settings.
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['nums'] = $new_instance['nums'];
			return $instance;
		}

		/*
		* Displays the widget settings controls on the widget panel.
		* Make use of the get_field_id() and get_field_name() function
		* when creating your form elements. This handles the confusing stuff.
		*/
		
		function form( $instance ) {
			
			// Set up some default widget settings
			$defaults = array(
				'title' => __( 'Brands', 'aps-text' ),
				'nums' => 'yes'
			);
			
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			
			<!-- Title input field -->
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'aps-text'); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			
			<!-- Show number of products input select -->
			<p>
				<label for="<?php echo $this->get_field_id( 'nums' ); ?>"><?php _e( 'Number of Products:', 'aps-text' ); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'nums' ); ?>" name="<?php echo $this->get_field_name( 'nums' ); ?>">
					<option value="yes" <?php if ($instance['nums'] == 'yes') echo 'selected="selected"'; ?>><?php _e( 'Display', 'aps-text' ); ?></option>
					<option value="no" <?php if ($instance['nums'] == 'no') echo 'selected="selected"'; ?>><?php _e( 'Don\'t Display', 'aps-text' ); ?></option>
				</select>
			</p>
			<?php
		}
	}

	// aps plugin sidebar
	function aps_plugin_sidebar() { ?>
		<div class="aps-side-box">
			<h3><?php _e('Check Out Our Latest Plugin', 'aps-text'); ?></h3>
			<div class="aps-side-content">
				<span class="aps-side-img">
					<a href="//j.mp/simp-modal">
					<img src="//cdn.webstudio55.com/media/simp-modal.jpg" alt="Simp Modal Window - WordPress Plugin" />
					</a>
				</span>
			</div>
		</div>
		
		<div class="aps-side-box">
			<h3><?php _e('Like / Share with Your Friends', 'aps-text'); ?></h3>
			<div class="aps-side-content">
				<div class="aps-social">
					<a class="twitter-share-button" href="https://twitter.com/share" data-url="http://codecanyon.net/item/arena-mobile-specs-wordpress-plugin/8674943?ref=Anjum" data-via="@WebStudio55" data-text="Check out this great item on #codecanyon APS Arena Products Store - WordPress Plugin" data-count="vertical">Tweet</a>
				</div>
				<div class="aps-social">
					<div class="fb-like" data-href="http://codecanyon.net/item/arena-mobile-specs-wordpress-plugin/8674943?ref=Anjum" data-layout="box_count" data-action="like" data-show-faces="true" data-share="false"></div>
				</div>
				<div class="aps-social">
					<div class="g-plusone" data-href="http://codecanyon.net/item/arena-mobile-specs-wordpress-plugin/8674943?ref=Anjum" data-size="tall"></div>
				</div>
				<div class="aps-social">
					<su:badge layout="5" location="http://codecanyon.net/item/arena-mobile-specs-wordpress-plugin/8674943?ref=Anjum"></su:badge>
				</div>
			</div>
			<script type="text/javascript">
			window.twttr=(function(d,s,id){var t,js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return}js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);return window.twttr||(t={_e:[],ready:function(f){t._e.push(f)}})}(document,"script","twitter-wjs"));
			(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));
			(function() { var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true; li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s); })();
			</script>
			<script src="https://apis.google.com/js/platform.js" async defer></script>
		</div>
		
		<?php if ($news = get_option('aps-latest-news')) { ?>
			<div class="aps-side-box">
				<h3><?php _e('Latest Updates', 'aps-text'); ?></h3>
				<div class="aps-side-content">
					<ul class="aps-news">
						<?php foreach ($news as $new) { ?>
							<li>
								<span class="aps-avatar">
									<img src="<?php echo $new['avat']; ?>" alt="Twitter User" width="32" height="32" />
								</span>
								<p class="aps-news-text"><?php echo $new['msg']; ?><br />
								<a class="aps-news-time" href="<?php echo $new['link']; ?>" target="_blank" rel="nofollow"><span style="font-size:11px;"> <?php echo $new['time']; ?></span></a></p>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		<?php }
	}