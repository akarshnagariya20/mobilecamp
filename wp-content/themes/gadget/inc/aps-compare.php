<?php if (!defined('APS_VER')) exit('restricted access');
/*
 * @package WordPress
 * @subpackage APS Products
*/

	// get list of products to compare
	$compList = aps_get_compare_list();
	$pid_count = count($compList);
	
get_header();
echo "Akarsh";
// get aps design settings
$design = get_aps_design();
$post_type = get_post_type(); ?>
	
	<div class="aps-container">
		<div class="aps-row clearfix">
			<div class="aps-content aps-content-<?php echo $design['content']; ?>">
				<?php if ($post_type == 'page' && $pid_count < 3) { ?>
					<div class="aps-comp-column">
						<div class="aps-row clearfix">
							<div class="aps-comp-search">
								<?php _e('Search and Add Products to Comapre', 'aps-text'); ?>
							</div>
							
							<div class="aps-comp-search">
								<div class="aps-comp-field">
									<input type="text" name="sp" class="aps-search-comp" value="" />
									<span class="aps-icon-search aps-pd-search"></span>
								</div>
							</div>
						</div>
					</div>
				<?php }
				
				// strat loop
				if (!empty($compList) && $pid_count > 1) {
                                     
					if ($pid_count == 1) { $span = 'aps-1co'; }
					elseif ($pid_count == 2) { $span = 'aps-2co'; }
					elseif ($pid_count == 3) { $span = 'aps-3co'; }
					elseif ($pid_count == 4) { $span = 'aps-4co'; }
					elseif ($pid_count == 5) { $span = 'aps-5co'; }
                                      
					
					// get attributes groups
					$groups = get_aps_groups();
					
					// get defined attributes
					$attributes = get_aps_attributes();
					
					// main labels
					$labels = array(
						'product' => __('Product Name', 'aps-text'),
						'image' => __('Product Image', 'aps-text'),
						'rating' => __('Our Rating', 'aps-text'),
						'brand' => __('Brand', 'aps-text')

 					);

					$data = array();
					foreach ($compList as $pid) {
					echo "<pre>";print_r($pid);echo "</pre>";
                                        exit;	
                                           // get post meta data by key
						$rating = get_product_rating_total($pid);
						$image = get_product_image(200, 200, $pid);
						
						$p_title = get_the_title($pid);
						$p_link = get_permalink($pid);
						if (!is_single()) {
							$remove = '<span class="aps-close-icon aps-icon-cancel aps-remove-compare" data-pid="' .$pid .'" title="' .__('Remove Compare', 'aps-text') .'"></span>';
						} else {
							$remove = null;
						}
						
						$main_title[] = $p_title;
						$brand = ($product_brand = get_product_brand($pid)) ? $product_brand : null;
						$brand_link = (isset($brand)) ? get_term_link($brand) : '';
						
						$data['product'][$pid] = '<h4 class="aps-comp-title"><a href="' .$p_link .'" title="' .$p_title .'">' .$p_title .'</a></h4>';
						$data['image'][$pid] = '<img class="aps-comp-thumb" src="' .$image['url'] .'" alt="' .$p_title .'" />' .$remove;
						$data['rating'][$pid] = '<span class="aps-comp-rating">' .$rating .'</span>';
						$data['brand'][$pid] = '<a href="' .$brand_link .'">' .(isset($brand) ? $brand->name : '') .'</a>';
					}
					
					$delm = ' ' .__('vs', 'aps-text') .' '; ?>
					
					<h2 class="aps-main-title"><?php echo implode($delm, $main_title); ?></h2>
					<?php if ($post_type == 'aps-comparisons') { ?>
						<div class="aps-column aps-group"><?php the_content(); ?></div>
					<?php } ?>
					<div class="aps-group">
						<ul class="aps-specs-list">
							<?php // print basic values
							foreach ($labels as $l_key => $label) { ?>
								<li>
									<strong class="aps-term"><?php echo $label; ?></strong>
									<div class="aps-attr-value">
										<?php foreach ($data[$l_key] as $vl) { ?>
											<div class="<?php echo $span; ?>"><?php echo $vl; ?></div>
										<?php } ?>
									</div>
								</li>
							<?php } ?>
						</ul>
					</div>
					
					<?php // start foreach loop
					foreach ($groups as $key => $group) {
						
						$specs = array();
						foreach ($compList as $pid) {
							// get post meta data by key
							$specs[$pid][$key] = get_aps_product_attributes($pid, $key);
						}

						// check if data is not empty
						if (!empty($specs) && $group['display'] == 'yes') { ?>
							<div class="aps-group">
								<h3 class="aps-group-title"><?php echo $group['name']; ?> <?php if ($design['icons']  == '1') { ?><span class="alignright <?php echo $group['icon']; ?>"></span><?php } ?></h3>
								<ul class="aps-specs-list">
									<?php // print devices specs
									foreach ($attributes[$key] as $attr_key => $attr) {
										$term = (isset($attr['info'])) ? $attr['info'] : null; ?>
										<li>
											<strong class="aps-term<?php if ($term) echo ' aps-tooltip'; ?>"><?php echo $attr['name']; ?></strong> 
											<?php if ($term) echo '<span class="aps-tooltip-data">' .$term .'</span>'; ?>
											<div class="aps-attr-value">
												<?php // print specs
												foreach ($compList as $pid) {
													$attr_val = (isset($specs[$pid][$key][$attr_key])) ? $specs[$pid][$key][$attr_key] : '';
													if ($attr['type'] == 'date') {
														$attr_val = (!empty($attr_val)) ? date('d F Y', strtotime($attr_val)) : '';
													} elseif ($attr['type'] == 'check') {
														$attr_val = ($attr_val == 'Yes') ? '<i class="aps-icon-check"></i>' : '<i class="aps-icon-cancel aps-icon-cross"></i>';
													} ?>
													<span class="<?php echo $span; ?>"><?php echo nl2br($attr_val); ?></span>
												<?php }?>
											</div>
										</li>
										<?php
									} ?>
								</ul>
							</div>
						<?php }
					} // end forach loop
				} ?>
			</div>
			
			<div class="aps-sidebar">
				<?php dynamic_sidebar('aps-sidebar'); ?>
			</div>
		</div>
		<?php if ($post_type == 'page') { ?>
			<script type="text/javascript">
			(function($) {
				var cinput = $(".aps-search-comp"),
				cparent = cinput.parent(),
				cul = (!!cparent.find(".aps-comp-results").length ? $(".aps-comp-results") : $("<ul class='aps-comp-results aps-wd-products'></ul>"));
				cinput.on("input propertychange", function(e) {
					var query = cinput.val();
					if (query.length > 1) {
						$.getJSON(
							ajaxurl + "?action=aps-search&num=12&type=compare&search=" + query,
							function(data) {
								if (data) {
									cul.empty();
									$.each(data, function(k, v) {
										cul.append(v)
									});
									cul.remove();
									cparent.append(cul);
								}
							}
						);
					} else {
						cul.empty();
					}
				}).blur(function() {
					setTimeout(function() {
						cul.hide()
					}, 500);
				}).focus(function() {
					cul.show();
				});
				
				// reload page
				$(document).on("click", ".aps-add-compare", function(e) {
					e.preventDefault();
					
					setTimeout(function(){
						location.reload();
					}, 2000);
				});
			})(jQuery);
			</script>
		<?php } ?>
	</div>
<?php get_footer(); ?>