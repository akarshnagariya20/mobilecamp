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
				<?php // get compare page link
				$comp_link = get_compare_page_link();
				// get settings
				$settings = get_aps_settings();
				
				// start the loop
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						$title = get_the_title(); ?>
						<h2 class="aps-main-title"><?php echo $title; ?></h2>
						<div class="aps-row">
							<?php // get product image
							$image = get_product_image(800, 800);
							$comp_count = count(aps_get_compare_list()); ?>
							<div class="aps-product-pic">
								<img class="image-zoom" src="<?php echo $image['url']; ?>" alt="<?php the_title_attribute(); ?>" data-zoom-image="<?php echo $image['url']; ?>" />
								<div class="aps-buttons-box">
									<a class="aps-btn-boxed aps-add-compare" href="#" data-pid="<?php echo $post->ID; ?>" data-msg="<?php echo '<strong>' .__('Success', 'aps-text') .':</strong> ' .__('You have added', 'aps-text') .' <strong>' .$title .'</strong> ' .__('to your', 'aps-text') .' <a href=\'' .$comp_link .'\'>' .__('comparison list', 'aps-text') .'</a>'; ?>"><i class="aps-icon-compare"></i> <?php _e('Add to Compare', 'aps-text'); ?></a>
									<a class="aps-btn-boxed aps-compare" href="<?php echo $comp_link; ?>"><i class="aps-icon-shuffle"></i> <?php _e('Compare', 'aps-text'); if ($comp_count > 0) echo ' <span>(' .$comp_count .')</span>'; ?></a>
								</div>
							</div>
							
							<div class="aps-main-features">
								<?php // get main features attributes
								$main_features = get_aps_features();
								// get the features values from post meta
								$features = get_aps_product_features($post->ID);
								
								if (!empty($features)) { ?>
									<ul class="aps-features aps-row clearfix">
										<?php foreach ($main_features as $feature_key => $feature) { ?>
											<li>
												<div class="aps-flipper">
													<div class="flip-front">
														<span class="aps-flip-icon aps-icon-<?php echo $feature['icon']; ?>"></span>
													</div>
													<div class="flip-back">
														<span class="aps-back-icon aps-icon-<?php echo $feature['icon']; ?>"></span><br />
														<strong><?php echo $feature['name']; ?></strong><br />
														<span><?php echo $features[$feature_key]; ?></span>
													</div>
												</div>
											</li>
										<?php } ?>
									</ul>
								<?php } ?>
							</div>
						</div>
						<?php // get tabs data from options
						$tabs = get_aps_tabs();
						$tabs_data = get_aps_product_tabs($post->ID);
						
						// get attributes groups
						$groups = get_aps_groups();
						// get defined attributes
						$attributes = get_aps_attributes();
						
						// get aps videos data
						$videos = get_aps_product_videos($post->ID);
						// get aps gallery data
						$images = get_aps_product_gallery($post->ID);
						// get aps offers data
						$offers = get_aps_product_offers($post->ID);
						
						$tabs_display = array(
							'overview' => true,
							'specs' => (!empty($attributes)) ? true : false,
							'reviews' => true,
							'videos' => (!empty($videos)) ? true : false,
							'gallery' => (!empty($images)) ? true : false,
							'offers' => (!empty($offers)) ? true : false,
							'custom1' => (!empty($tabs_data['tab1'])) ? true : false,
							'custom2' => (!empty($tabs_data['tab2'])) ? true : false,
							'custom3' => (!empty($tabs_data['tab3'])) ? true : false
						);
						
						if ($tabs) { ?>
							<ul class="aps-tabs">
								<?php $tb = 0;
								foreach ($tabs as $tb_key => $tab) {
									if (($tab['display'] == 'yes') && ($tabs_display[$tb_key] == true)) {
										$tb++; ?>
										<li<?php if ($tb == 1) echo ' class="active"'; ?>><a href="#aps-<?php echo $tb_key; ?>"><?php echo $tab['name']; ?></a></li>
									<?php }
								} ?>
							</ul>
							
							<div class="aps-tab-container">
								<?php foreach ($tabs as $tb_key => $tab) {
									if ($tab['display'] == 'yes') { ?>
										<div id="aps-<?php echo $tb_key; ?>" class="aps-tab-content">
											<?php if ($tb_key == 'overview') { ?>
												<div class="aps-column">
													<?php // product rating
													$product_rating = get_product_rating($post->ID);
													$total_bar = get_product_rating_total($post->ID);
													$total_color = aps_rating_bar_color(round($total_bar)); ?>
													<div class="aps-rating-card">
														<div class="aps-rating-text-box">
															<h3 class="no-margin uppercase"><?php echo $settings['rating-title']; ?></h3>
															<p><em><?php echo $settings['rating-text']; ?></em></p>
														</div>
														
														<div class="aps-rating-bar-box">
															<div class="aps-overall-rating" data-bar="true" data-rating="<?php echo $total_bar; ?>">
																<span class="aps-total-wrap">
																	<span class="aps-total-bar <?php echo $total_color; ?>" data-type="bar"></span>
																</span>
																<span class="aps-rating-total" data-type="num"><?php echo $total_bar; ?></span>
															</div>
														</div>
														<div class="clear"></div>
														
														<ul class="aps-pub-rating aps-row clearfix">
															<?php // get rating bars attributes
															$rating_bars = get_aps_rating_bars();
															foreach ($product_rating as $rk => $rating) {
																if ($rk !== 'total') {
																$color = aps_rating_bar_color($rating); ?>
																<li>
																	<div class="aps-rating-box" data-bar="true" data-rating="<?php echo $rating; ?>">
																		<span class="aps-rating-asp">
																			<strong><?php echo $rating_bars[$rk]['label']; ?></strong>
																			<span class="aps-rating-num"><span class="aps-rating-fig" data-type="num"><?php echo $rating; ?></span> / 10</span>
																		</span>
																		<span class="aps-rating-wrap">
																			<span class="aps-rating-bar <?php echo $color; ?>" data-type="bar"></span>
																		</span>
																	</div>
																</li>
																<?php }
															} ?>
														</ul>
													</div>
												</div>
												
												<div class="aps-column">
													<?php the_content(); ?>
												</div>
											<?php } elseif ($tb_key == 'specs') { ?>
												<div class="aps-column">
													<h2 class="aps-tab-title"><?php echo $title; ?> - <?php echo $tab['name']; ?></h2>
													<?php if (!empty($groups)) {
														// get product brand
														$brand = get_product_brand($post->ID);
														$brand_link = get_term_link($brand);
														
														// start foreach loop
														foreach ($groups as $key => $group) {
															if ($group['display'] == 'yes') {
																// get post meta data by key
																$data = get_aps_product_attributes($post->ID, $key);
																
																// check if data is an array
																if (!empty($data)) { ?>
																	<div class="aps-group">
																		<h3 class="aps-group-title"><?php echo $group['name']; ?> <?php if ($design['icons']  == '1') { ?><span class="alignright aps-icon-<?php echo $group['icon']; ?>"></span><?php } ?></h3>
																		<ul class="aps-specs-list">
																			<?php foreach ($attributes[$key] as $attr_key => $attr_val) {
																				// get attribute data
																				$value = $data[$attr_key];
																				if (!empty($value)) {
																					
																					// check if value is date
																					if ($attr_val['type'] == 'date') {
																						$value = date('d F Y', strtotime($value));
																					} elseif ($attr_val['type'] == 'check') {
																						$value = ($value == 'Yes') ? '<i class="aps-icon-check"></i>' : '<i class="aps-icon-cancel aps-icon-cross"></i>';
																					} ?>
																					<li>
																						<strong class="aps-term<?php if (!empty($attr_val['info'])) echo ' aps-tooltip'; ?>"><?php echo $attr_val['name']; ?></strong> 
																						<?php if (!empty($attr_val['info'])) echo '<span class="aps-tooltip-data">' .$attr_val['info'] .'</span>'; ?>
																						<div class="aps-attr-value">
																							<span class="aps-1co"><?php echo nl2br($value); ?></span>
																						</div>
																					</li>
																				<?php }
																			} ?>
																		</ul>
																	</div>
																<?php }
															}
														} // end foreach loop
													} // end if ?>
												</div>
											<?php } elseif ($tb_key == 'reviews') { ?>
												
												<h2 class="aps-tab-title"><?php echo $title; ?> - <?php echo $tab['name']; ?></h2>
												<?php comments_template();
												
											} elseif ($tb_key == 'videos') { ?>
												
												<h2 class="aps-tab-title"><?php echo $title; ?> - <?php echo $tab['name']; ?></h2>
												<?php // check if videos
												if (!empty($videos)) {
													$video_hosts = array(
														'youtube' => array('url' => '//www.youtube.com/embed/', 'pms' => '?showinfo=0&amp;rel=0&amp;wmode=transparent'),
														'dailymotion' => array('url' => '//www.dailymotion.com/embed/video/', 'pms' => '?info=0&amp;related=0&amp;wmode=transparent'),
														'vimeo' => array('url' => '//player.vimeo.com/video/', 'pms' => '?byline=0&amp;title=0&amp;portrait=0&amp;wmode=transparent'),
														'break' => array('url' => '//www.break.com/embed/', 'pms' => '?embed=1&amp;wmode=transparent'),
														'metacafe' => array('url' => '//www.metacafe.com/embed/', 'pms' => '?title=0&amp;wmode=transparent')
													); ?>
													<div class="aps-product-videos aps-row">
														<?php foreach ($videos as $video) {
															$host = $video['host'];
															$vid = $video['vid']; ?>
															<div class="aps-video-col">
																<div class="aps-video-box">
																	<div class="aps-video">
																		<iframe src="<?php echo $video_hosts[$host]['url'] .$vid .$video_hosts[$host]['pms']; ?>" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
																	</div>
																</div>
															</div>
														<?php } ?>
													</div>
												<?php }
												
											} elseif ($tb_key == 'gallery') { ?>
												
												<h2 class="aps-tab-title"><?php echo $title; ?> - <?php echo $tab['name']; ?></h2>
												<?php // check if images
												if (!empty($images)) { ?>
													<div class="aps-gallery-container">
														<ul class="aps-gallery-thumbs aps-row clearfix">
															<?php foreach ($images as $image) {
																$img = get_product_image(800, 800, '', (int) $image);
																$thumb = get_product_image(220, 220, '', (int) $image);
																$alt = get_post_meta((int) $image, '_wp_attachment_image_alt', true);
																$alt = ($alt) ? $alt : $title; ?>
																<li>
																	<a class="aps-lightbox" href="<?php echo $img['url']; ?>" title="<?php echo $alt; ?>" data-lightbox-gallery="gallery">
																		<img class="aps-gallery-thumb" src="<?php echo $thumb['url']; ?>" alt="<?php echo $alt; ?>" />
																		<span class="aps-gallery-zoom">
																			<span class="aps-icon-search"></span>
																			<strong class="aps-image-title"><?php echo $alt; ?></strong>
																		</span>
																	</a>
																</li>
															<?php } ?>
														</ul>
													</div>
												<?php }
											} elseif ($tb_key == 'offers') { ?>
												
												<div class="aps-column">
													<h2 class="aps-tab-title"><?php echo $title; ?> - <?php echo $tab['name']; ?></h2>
													<?php // loop offers
													if (!empty($offers)) {
														// get aps affiliate stores
														$stores = get_aps_affiliates(); ?>
														<ul class="aps-offers-list clearfix">
															<?php foreach ($offers as $offer) { ?>
																<li>
																	<span class="aps-offer-thumb">
																		<img src="<?php echo $stores[$offer['store']]['logo']; ?>" alt="<?php echo $stores[$offer['store']]['name']; ?>" />
																	</span>
																	<span class="aps-offer-title"><?php echo $offer['title']; ?></span>
																	<span class="aps-offer-price">
																		<?php echo $offer['price']; ?>
																	</span>
																	<span class="aps-offer-link"><br />
																		<a class="aps-button aps-btn-skin" href="<?php echo $offer['url']; ?>" target="_blank" rel="nofollow"><?php _e('View Offer', 'aps-text'); ?></a>
																	</span>
																</li>
															<?php } ?>
														</ul>
													<?php } ?>
												</div>
											<?php } elseif ($tb_key == 'custom1') { ?>
												
												<div class="aps-column">
													<h2 class="aps-tab-title"><?php echo $title; ?> - <?php echo $tab['name']; ?></h2>
													<?php echo wpautop(do_shortcode($tabs_data['tab1'])); ?>
												</div>
												
											<?php } elseif ($tb_key == 'custom2') { ?>
												
												<div class="aps-column">
													<h2 class="aps-tab-title"><?php echo $title; ?> - <?php echo $tab['name']; ?></h2>
													<?php echo wpautop(do_shortcode($tabs_data['tab2'])); ?>
												</div>
												
											<?php } elseif ($tb_key == 'custom3') { ?>
												
												<div class="aps-column">
													<h2 class="aps-tab-title"><?php echo $title; ?> - <?php echo $tab['name']; ?></h2>
													<?php echo wpautop(do_shortcode($tabs_data['tab3'])); ?>
												</div>
												
											<?php } ?>
										</div>
									<?php }
								} ?>
							</div>
						<?php }
					endwhile;
					
					// get more products by same brand
					$args = array(
						'post_type' => 'aps-products',
						'posts_per_page' => $settings['more-num'],
						'orderby' => 'rand',
						'aps-brands' => $brand->slug,
						'post__not_in' => array($post->ID)
					);
					
					$related = new WP_Query($args);
					
					if ($related->have_posts()) { ?>
						<div class="aps-column">
							<h3><?php _e('More Products from', 'aps-text'); ?> <a href="<?php echo $brand_link; ?>"><?php echo $brand->name; ?></a></h3>
							<ul class="aps-related-products aps-row clearfix">
								<?php while ($related->have_posts()) {
									$related->the_post(); ?>
									<li>
										<?php // get related product thumbnail
										$rd_thumb = get_product_image(60, 60);
										
										// get related product reviews 
										$rd_reviews = get_comments_number($post->ID); ?>
										<div class="aps-rd-box">
											<span class="aps-rd-thumb">
												<img src="<?php echo $rd_thumb['url']; ?>" alt="<?php the_title_attribute(); ?>" />
											</span>
											<span class="aps-rd-title"><a href="<?php the_permalink(); ?>"><strong><?php the_title(); ?></strong></a></span><br />
											<span class="aps-rd-reviews"><?php echo $rd_reviews; ?> <?php echo ($rd_reviews == 1) ? __('Review', 'aps-text') : __('Reviews', 'aps-text'); ?></span><br />
											<span class="aps-rd-specs"><a href="<?php the_permalink(); ?>"><?php _e('View specs', 'aps-text'); ?> &rarr;</a></span>
										</div>
									</li>
								<?php } ?>
							</ul>
						</div>
					<?php }
					// rest query data
					wp_reset_postdata();
					
					// get zoom settings
					$zoom = get_option('aps-zoom');
					
					// get gallery (lightbox) settings
					$lightbox = get_option('aps-gallery'); ?>
					<script type="text/javascript">
					jQuery(document).ready(function($) {
						// aps tabs
						$(".aps-tab-content:first").show();
						$("ul.aps-tabs li").click(function(e) {
							$(this).addClass("active");
							$(this).siblings().removeClass("active");
							//$(".aps-tab-content").hide();
							var activeTab = $(this).find("a").attr("href");
							$(activeTab).fadeIn(300);
							$(".aps-tab-content").not(activeTab).hide();
							$(window).trigger("rating");
							e.preventDefault();
						});
						
						<?php if ($zoom['enable']) { ?>
						// zoom images on mouseover
						$(".image-zoom").elevateZoom({
							lensShape: '<?php echo $zoom['lensShape']; ?>',
							lensSize: <?php echo $zoom['lensSize']; ?>,
							lensBorder: <?php echo $zoom['lensBorder']; ?>,
							zoomType : '<?php echo $zoom['zoomType']; ?>',
							scrollZoom : <?php echo ($zoom['scrollZoom'] ? 'true' : 'false'); ?>,
							easing : <?php echo ($zoom['easing'] ? 'true' : 'false'); ?>,
							easingAmount : <?php echo $zoom['easingAmount']; ?>,
							responsive : <?php echo ($zoom['responsive'] ? 'true' : 'false'); ?>,
							zoomWindowWidth : <?php echo $zoom['zoomWindowWidth']; ?>,
							zoomWindowHeight : <?php echo $zoom['zoomWindowHeight']; ?>
							
						});
						<?php } ?>
						
						<?php if ($lightbox['enable']) { ?>
						// nivo lightbox
						$(".aps-lightbox").nivoLightbox({
							effect: '<?php echo $lightbox['effect']; ?>',
							keyboardNav: <?php echo ($lightbox['nav'] ? 'true' : 'false'); ?>,
							clickOverlayToClose: <?php echo ($lightbox['close'] ? 'true' : 'false'); ?>
							
						});
						<?php } ?>
					});
					</script>
				<?php endif; ?>
			</div>
			
			<div class="aps-sidebar">
				<?php dynamic_sidebar('aps-sidebar'); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>