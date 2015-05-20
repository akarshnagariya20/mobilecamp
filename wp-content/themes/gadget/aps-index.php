<?php if (!defined('APS_VER')) exit('restricted access');
/*
 * @package WordPress
 * @subpackage APS Products
*/
get_header();
<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress

 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/bootstrap.min.css">
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
    <![endif]-->
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed">
    <header id="masthead" class="site-header" role="banner">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                        <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php header_image(); ?>" alt="logo" title="Gadget Recharge"></a> </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <?php dynamic_sidebar( "header-right"); ?>
                        </ul>

                    </div>
                </div>
                <!-- /.container-fluid -->
        </nav>
        <div class="header_bottom">
            <div class="container">
                <div class="row">
                    <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
                </div>
            </div>
        </div>
        <div id="banner">
            <div class="banner">
                <div class="mbl-functn">
                    <div class="lg-circle">
                        <div class="sm-circle">
                            COMPARE
                        </div>


                        <div class="sm-crcl"><a href="#">3G</a></div>
                        <div class="sm1-crcl"><a href="#">WiFi</a></div>
                        <div class="sm2-crcl"><a href="#">Dual sim</a></div>
                        <div class="sm3-crcl"><a href="#">Qwerty</a></div>
                        <div class="sm4-crcl"><a href="#">Touch</a></div>
                        <div class="sm5-crcl"><a href="#">Slim</a></div>
                        <div class="sm6-crcl"><a href="#">windows phone</a></div>
                        <div class="sm7-crcl"><a href="#">Android</a></div>
                    </div>
                </div>

                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                    </ol>

                    <?php $args = array(
                        'posts_per_page'   => 3,
                        'category_id'      => 10,
                        'category_name'    => 'Slider',
                        'post_type'        => 'post',
                        'post_status'      => 'publish',
                        'suppress_filters' => true ); ?>
                    <?php query_posts($args); ?>
                    <!-- Do special_cat stuff... -->

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <?php while (have_posts()) : the_post(); ?>
                            <div class="item ">
                            <?php the_post_thumbnail('full');?>
                            <div class="carousel-caption">
                                <div class="banner_text">
                                <h2><?php the_title();?></h2><dfn><?php the_content();?></dfn>
                                    <a href="#">COMPARE NOW <i> &nbsp;</i></a>
                                </div>
                            </div></div><?php endwhile;?>
                    </div>
                        <!-- Controls -->
                        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <script>
                      
                       <!-- $(window).load(function(){-->
                            jQuery( ".carousel-inner .item:first-child" ).addClass( "active" );
                        <!--});-->
                    </script>
 
                </div>
            
    </header><!-- #masthead -->

    <div id="main" class="wrapper main-content">









// get aps design settings
$design = get_aps_design(); ?>
	
	<div class="aps-container">
		<div class="aps-row clearfix">
			<div class="aps-content aps-content-<?php echo $design['content']; ?>">
				<?php // APS Index Page Template
				global $wp, $wp_query;
				
				if (get_query_var('page')) {
					$paged = get_query_var('page');
				} elseif (get_query_var('paged')) {
					$paged = get_query_var('paged');
				} else {
					$paged = 1;
				}
				
				$settings = get_aps_settings();
				$index_link = add_query_arg( null, null, home_url( $wp->request ) .'/' );
				$sort = isset($_GET['sort']) ? trim(strip_tags($_GET['sort'])) : null;
				$display = isset($_COOKIE['aps_display']) ? trim(strip_tags($_COOKIE['aps_display'])) : 'grid';
				$perpage = ($num = $settings['num-products']) ? $num : 12;
				$url_args = array();
				
				// query params
				$args = array(
					'post_type' => 'aps-products',
					'posts_per_page' => $perpage,
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
						$unsort_url = $index_link .'?filters=' .$url_args['filters'];
						$sort_url = $index_link .'?filters=' .$url_args['filters'] .'&amp;sort=';
						$filter_url = $index_link .'?filters=';
					} else if ($url_args['sort']) {
						$unsort_url = $index_link;
						$sort_url = $index_link .'?sort=';
						$filter_url = $index_link .'?sort=' .$url_args['sort'] .'&amp;filters=';
					}
				} else {
					$unsort_url = $index_link;
					$sort_url = $index_link .'?sort=';
					$filter_url = $index_link .'?filters=';
				}
				
				$products = new WP_Query($args); ?>
				
				<h1 class="aps-main-title"><?php echo $settings['index-title']; ?></h1>
				
				<?php // get compare page link
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
					
					<div class="aps-brands-controls aps-dropdown">
						<?php // get aps brands
						$brands = get_all_aps_brands($settings['brands-sort']);
						if ($brands) { ?>
							<span class="aps-current-dp"><?php echo $settings['brands-dp']; ?></span>
							<ul>
								<?php foreach ($brands as $brand) { ?>
									<li><a href="<?php echo get_term_link($brand); ?>"><?php echo $brand->name; ?></a></li>
								<?php } ?>
							</ul>
							<span class="aps-select-icon aps-icon-down"></span>
						<?php } ?>
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
										<img src="<?php echo $thumb['url']; ?>" width="400" height="400" alt="<?php the_title_attribute(); ?>" />
									</span>
									
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
                                    <h2 class="aps-product-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo $title; ?></a></h2>
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
					<p><?php _e('Nothing to display yet.', 'aps-text'); ?></p>
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