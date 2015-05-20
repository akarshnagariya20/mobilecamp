<?php if (!defined('APS_VER')) exit('restricted access');
/*
 * @package WordPress
 * @subpackage APS Products
*/
get_header();

// get aps design settings
$design = get_aps_design(); ?>
	
	<div class="aps-container">
		<div class="aps-row clearfix">
			<div class="aps-content aps-content-<?php echo $design['content']; ?>">
				<?php // APS search archive
				global $wp, $wp_query;
				
				$settings = get_aps_settings();
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				$key = get_query_var('s');
				$search_link = add_query_arg( $wp->query_string, '', home_url( $wp->request ) .'/' );
				$sort = isset($_GET['sort']) ? trim(strip_tags($_GET['sort'])) : null;
				$display = isset($_COOKIE['aps_display']) ? trim(strip_tags($_COOKIE['aps_display'])) : 'grid';
				$perpage = ($num = $settings['num-products']) ? $num : 12;
				$url_args = array();
				
				// query paraps
				$args = array(
					'post_type' => 'aps-products',
					'posts_per_page' => $perpage,
					'aps_title' => $key,
					'paged' => $paged
				);
				
				// get filters query params
				if (isset($_GET['filters'])) {
					$get_filters = trim($_GET['filters']);
					$filters = explode('_', $get_filters);
					
					if (!empty($filters)) {
						$taxonomies = array();
						$filters_terms = array();
						foreach ($filters as $filter) {
							$tax = explode('.', $filter);
							$terms = explode(',', $tax[1]);
							
							$taxonomies[] = array(
								'taxonomy' => 'aps-' .$tax[0],
								'field' => 'slug',
								'terms' => $terms
							);
							
							$filters_terms[$tax[0]] = $terms;
						}
						
						// add filters in query args
						$args['tax_query'] = array(
							'relation' => 'OR', 
							$taxonomies
						);
					}
					
					$url_args['filters'] = $get_filters;
				}
					
				// sort posts by user input
				if ($sort) {
					if ($sort == 'name-az') {
						$args['orderby'] = 'title';
						$args['order'] = 'ASC';
					} elseif ($sort == 'name-za') {
						$args['orderby'] = 'title';
						$args['order'] = 'DESC';
					} elseif ($sort == 'rating-hl') {
						$args['orderby'] = 'meta_value_num';
						$args['meta_key'] = 'aps-product-rating-total';
					} elseif ($sort == 'rating-lh') {
						$args['orderby'] = 'meta_value_num';
						$args['order'] = 'ASC';
						$args['meta_key'] = 'aps-product-rating-total';
					} elseif ($sort == 'reviews-hl') {
						$args['orderby'] = 'comment_count';
					} elseif ($sort == 'reviews-lh') {
						$args['orderby'] = 'comment_count';
						$args['order'] = 'ASC';
					}
					
					$url_args['sort'] = $sort;
				}
					
				// product sorting
				$sorts = array(
					'default' => __('Date (default)', 'aps-text'),
					'name-az' => __('Name (A-Z)', 'aps-text'),
					'name-za' => __('Name (Z-A)', 'aps-text'),
					'rating-hl' => __('Rating (high > low)', 'aps-text'),
					'rating-lh' => __('Rating (low > high)', 'aps-text'),
					'reviews-hl' => __('Reviews (high > low)', 'aps-text'),
					'reviews-lh' => __('Reviews (low > high)', 'aps-text'),
				);
				
				// create urls using query string params 
				if (!empty($url_args)) {
					if ($url_args['filters']) {
						$unsort_url = $search_link .'&amp;filters=' .$url_args['filters'];
						$sort_url = $search_link .'&amp;filters=' .$url_args['filters'] .'&amp;sort=';
						$filter_url = $search_link .'&amp;filters=';
					} else if ($url_args['sort']) {
						$unsort_url = $search_link;
						$sort_url = $search_link .'&amp;sort=';
						$filter_url = $search_link .'&amp;sort=' .$url_args['sort'] .'&amp;filters=';
					}
				} else {
					$unsort_url = $search_link;
					$sort_url = $search_link .'&amp;sort=';
					$filter_url = $search_link .'&amp;filters=';
				}
				
				$products = new WP_Query($args); ?>
				
				<h1 class="aps-main-title"><?php echo str_replace('%term%', $key, $settings['search-title']); ?></h1>
				
				<?php  // get compare page link
				$comp_link = get_compare_page_link(); ?>
				
				<div class="aps-column">
					<div class="aps-display-controls">
						<span><?php _e('Display', 'aps-text'); ?>:</span>
						<ul>
							<li><a class="aps-display-grid aps-icon-grid<?php if ($display == 'grid') echo ' selected'; ?>" title="<?php _e('Grid View', 'aps-text'); ?>"></a></li>
							<li><a class="aps-display-list aps-icon-list<?php if ($display == 'list') echo ' selected'; ?>" title="<?php _e('List View', 'aps-text'); ?>"></a></li>
						</ul>
					</div>
					
					<div class="aps-sort-controls aps-dropdown">
						<span class="aps-current-dp"><?php echo (isset($sort)) ? $sorts[$sort] : $sorts['default']; ?></span>
						<ul>
							<?php foreach ($sorts as $sk => $sv) {
								if ($sk == 'default' && $sort) { ?>
									<li><a href="<?php echo $unsort_url; ?>"><?php echo $sv; ?></a></li>
								<?php } elseif ($sk != 'default' && $sk != $sort) { ?>
									<li><a href="<?php echo $sort_url .$sk; ?>"><?php echo $sv; ?></a></li>
								<?php }
							} ?>
						</ul>
						<span class="aps-select-icon aps-icon-down"></span>
					</div>
					
					<div class="aps-filters-control">
						<a class="aps-filters-sw" href=""><?php echo $settings['filter-title']; ?> <i class="aps-icon-down"></i></a>
					</div>
				</div>
				<?php // get aps filters
				$filters = get_aps_filters();
				$term_ids = array();
				
				if (!empty($filters)) { ?>
					<div class="aps-filters aps-column">
						<span class="aps-filters-arrow"></span>
						<ul class="aps-filters-list">
							<?php foreach ($filters as $filter) { ?>
								<li>
									<?php // get filter slug
									$filter_cbs = $filter['slug'];
									$filter_slug = 'aps-' .$filter_cbs;
									
									// get filter terms
									$filter_terms = get_terms($filter_slug);
									$filter_tax = ($filters_terms[$filter_cbs]) ? $filters_terms[$filter_cbs] : null;
									
									// print all terms fields
									if (!empty($filter_terms)) { ?>
										<h5><?php echo $filter['name']; ?></h5>
										<?php foreach ($filter_terms as $term) {
											$checked = false;
											if (!empty($filter_tax) && in_array($term->slug, $filter_tax)) {
												$checked = true;
											} ?>
											<label class="aps-cb-label"><input type="checkbox" class="aps-filter-cb" name="<?php echo $filter_cbs; ?>" value="<?php echo $term->slug; ?>"<?php if ($checked) echo ' checked="checked"'; ?> /><span class="aps-cb-holder<?php if ($checked) echo ' aps-cb-active'; ?>"><i class="aps-icon-check"></i></span> <?php echo $term->name; ?></label> 
										<?php }
									} ?>
								</li>
							<?php } ?>
						</ul>
						<button class="aps-button aps-btn-skin aps-filter-submit alignright" data-url="<?php echo $filter_url; ?>"><i class="aps-icon-search"></i> <?php _e('Search', 'aps-text'); ?></button>
					</div>
				<?php }
				
				// start the loop
				if ( $products->have_posts() ) : ?>
					<ul class="aps-products aps-row clearfix <?php echo ($display == 'grid') ? 'aps-products-grid' : 'aps-products-list'; ?>">
						<?php while ( $products->have_posts() ) :
							$products->the_post(); ?>
							<li>
								<div class="aps-product-box">
									<?php // get product thumbnail
									$thumb = get_product_image(400, 400);
									// get main features attributes
									$main_features = get_aps_features();
									
									$features = get_aps_product_features($post->ID);
									$rating = get_product_rating_total($post->ID);
									$title = get_the_title(); ?>
									<span class="aps-product-thumb">
										<img src="<?php echo $thumb['url']; ?>" width="300" height="400" alt="<?php the_title_attribute(); ?>" />
									</span>
									<h2 class="aps-product-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo $title; ?></a></h2>
									<span class="aps-view-info aps-icon-info"></span>
									<div class="aps-product-details">
										<?php if (!empty($features)) { ?>
											<ul>
												<?php foreach ($main_features as $feature_key => $feature) { ?>
													<li><strong><?php echo $feature['name']; ?>:</strong> <?php echo $features[$feature_key]; ?></li>
												<?php } ?>
												<li class="aps-specs-link"><a href="<?php the_permalink(); ?>"><?php _e('View Details', 'aps-text'); ?> &rarr;</a></li>
											</ul>
										<?php } ?>
										<span class="aps-comp-rating"><?php echo $rating; ?></span>
									</div>
								</div>
								<div class="aps-buttons-box">
									<a class="aps-btn-boxed aps-add-compare" href="#" data-pid="<?php echo $post->ID; ?>" data-msg="<?php echo '<strong>' .__('Success', 'aps-text') .':</strong> ' .__('You have added', 'aps-text') .' <strong>' .$title .'</strong> ' .__('to your', 'aps-text') .' <a href=\'' .$comp_link .'\'>' .__('comparison list', 'aps-text') .'</a>'; ?>" title="<?php _e('Add to Compare', 'aps-text'); ?>"><i class="aps-icon-compare"></i></a>
									<a class="aps-btn-boxed aps-add-cart" href="#" data-pid="<?php echo $post->ID; ?>" title="<?php _e('Add to Cart', 'aps-text'); ?>"><i class="aps-icon-cart"></i></a>
								</div>
							</li>
						<?php endwhile; ?>
					</ul>
					<?php // pagination
					// need an unlikely integer
					$big = 999999999;
					$paginate = paginate_links(
						array(
							'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format' => '?paged=%#%',
							'end_size' => 3,
							'mid_size' => 3,
							'current' => max( 1, $paged ),
							'total' => $products->max_num_pages
						)
					);
					// print paginate links
					echo ($paginate) ? '<div class="aps-pagination">' .$paginate .'</div>' : '';
				else: ?>
					<p><?php _e('No Search Results found for your query.', 'aps-text'); ?></p>
				<?php endif;
				// reset query data
				wp_reset_postdata(); ?>
			</div>
			
			<div class="aps-sidebar">
				<?php dynamic_sidebar('aps-sidebar'); ?>
			</div>
		</div>
		<script type="text/javascript">
		(function($) {
			$(".aps-filters-sw").click(function(e) {
				$(".aps-filters").slideToggle();
				e.preventDefault();
			});
			
			$(".aps-filter-submit").click(function() {
				var url = $(this).data("url"),
				filters = [],
				filters_query = [];
				$(".aps-filter-cb:checked").each(function(e) {
					var filter_name = $(this).attr("name"),
					filter_values = [];
					
					$("[name='" +filter_name+ "']:checked").each(function() {
						filter_values.push(this.value);
					});
					
					if ($.inArray(filter_name, filters) < 0) {
						filters.push(filter_name);
						filters_query.push(filter_name + "." + filter_values.join(","));
					}
				});
				
				if (filters.length !== 0) {
					filters_query = filters_query.join("_");
					location = url + filters_query;
				}
			});
		})(jQuery);
		</script>
	</div>
<?php get_footer(); ?>