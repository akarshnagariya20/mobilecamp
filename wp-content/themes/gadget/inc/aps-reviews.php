<?php if (!defined('APS_VER')) exit('restricted access');
/*
 * @package WordPress
 * @subpackage APS Products
 * Reviews are comments, so we'll call comments here
*/
	// rating range input bars
	$rating_bars = get_aps_rating_bars();

	// check if post have comments
	if ( have_comments() ) {
		
		// get settings
		$settings = get_aps_settings();
		
		$args = array(
			'post_id' => $post->ID,
			'type' => 'review',
		);
		
		$reviews = get_comments($args);
		$count = 0;
		
		$overall = array('total' => '');
		foreach ($rating_bars as $bar_key => $bar_val) {
			$overall[$bar_key] = '';
		}
		
		foreach ($reviews as $review) {
			$reviewRating = get_comment_meta($review->comment_ID, 'aps-review-rating', true);
			
			foreach ($reviewRating as $key => $value) {
				$overall[$key] += $value;
			}
			$count++;
		}
		
		$overall_bar = $overall['total'] / $count;
		$overall_color = aps_rating_bar_color(round($overall_bar));
		$num = '<strong>' .$count .'</strong>'; ?>
		
		<div class="aps-rating-card">
			<div class="aps-rating-text-box">
				<h3 class="no-margin uppercase"><?php echo $settings['user-rating-title']; ?></h3>
				<p><em><?php echo str_replace('%num%', $num, $settings['user-rating-text']); ?></em></p>
			</div>
			
			<div class="aps-rating-bar-box">
				<div class="aps-overall-rating" data-bar="true" data-rating="<?php echo $overall_bar; ?>">
					<span class="aps-total-wrap">
						<span class="aps-total-bar <?php echo $overall_color; ?>" data-type="bar"></span>
					</span>
					<span class="aps-rating-total" data-type="num"><?php echo $overall_bar; ?></span>
				</div>
			</div>
			<div class="clear"></div>
			
			<ul class="aps-pub-rating aps-row">
				<?php foreach ($overall as $ok => $ov) {
					if ($ok !== 'total') {
						$ovrt = $ov / $count;
						$color = aps_rating_bar_color(round($ovrt)); ?>
						<li>
							<div class="aps-rating-box" data-bar="true" data-rating="<?php echo $ovrt; ?>">
								<span class="aps-rating-asp">
									<strong><?php echo ucwords(str_replace('-', ' ', $ok)); ?></strong>
									<span class="aps-rating-num"><span class="aps-rating-fig" data-type="num"><?php echo $ovrt; ?></span> / 10</span>
								</span>
								<span class="aps-rating-wrap">
									<span class="aps-rating-bar <?php echo $color; ?>" data-type="bar"></span>
								</span>
							</div>
						</li>
					<?php }
				} ?>
			</ul>
			<div class="clear"></div>
			
			<div class="aps-post-box">
				<a class="aps-button aps-btn-black" href="#apsReviewFields"><?php _e('Post a Review', 'aps-text'); ?></a>
				<span class="aps-review-info"><?php echo $settings['post-review-note']; ?></span>
			</div>
		</div>
		
		<ol class="aps-reviews-list">
			<?php // Callback to Comments/Reviews
			wp_list_comments( 'type=review&callback=aps_product_reviews' ); ?>
		</ol>
		
		<?php // Are there comments to navigate through?
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
			<div class="aps-reviews-nav">
				<p class="alignleft"><?php previous_comments_link( __( '&larr; Older Reviews', 'aps-text' ) ); ?></p>
				<p class="alignright"><?php next_comments_link( __( 'Newer Reviews &rarr;', 'aps-text' ) ); ?></p>
			</div>
		<?php }
	}
	
	// If comments are not closed
	if (comments_open()) { ?>
		<form id="apsReviewForm" action="#" method="post">
			<ul id="apsReviewFields">
				<li>
					<h3 class="no-margin uppercase">
						<?php if (have_comments()) { _e('Add a Review', 'aps-text'); } else { _e('Be the first to add a Review', 'aps-text'); } ?> 
					</h3>
					<p><em><?php _e('Please post a user review only if you have / had this device.', 'aps-text'); ?></em></p>
				</li>
				<?php // check if not a loggedin user
				if (!is_user_logged_in()) { ?>
					<li>
						<label for="aps-name"><?php _e('Your Name', 'aps-text'); ?> <span class="required">*</span></label>
						<input type="text" name="aps-name" id="aps-name" class="aps-text" value="" />
					</li>
					<li>
						<label for="aps-email"><?php _e('Your Email', 'aps-text'); ?> <span class="required">*</span></label>
						<input type="text" name="aps-email" id="aps-email" class="aps-text" value="" />
					</li>
				<?php } ?>
				<li>
					<label for="aps-review-title"><?php _e('Review Title', 'aps-text'); ?> <span class="required">*</span></label>
					<input type="text" name="aps-title" id="aps-review-title" class="aps-text" value="" />
				</li>
				<li>
					<label for="aps-review-text"><?php _e('Review Text', 'aps-text'); ?> <span class="required">*</span></label>
					<textarea name="aps-review" id="aps-review-text" class="aps-textarea"></textarea>
				</li>
				<li><h4 class="no-margin"><?php _e('Rate this device', 'aps-text'); ?></h4></li>
				<?php // loop through rating bars
				foreach ($rating_bars as $key => $bar) { ?>
					<li>
						<span class="aps-rating-label">
							<label class="aps-tooltip"><?php echo $bar['label']; ?>:</label>
							<span class="aps-tooltip-data"><?php echo $bar['info']; ?></span>
						</span>
						<div class="aps-rating-input">
							<input type="text" name="rating[<?php echo $key; ?>]" data-slider="true" data-slider-range="0,10" data-slider-step="1" data-slider-snap="true" data-slider-highlight="true" data-slider-theme="aps" value="<?php echo $bar['value']; ?>" />
						</div>
					</li>
				<?php } ?>
				<li>
					<label><?php _e('Average Rating', 'aps-text'); ?></label>
					<span class="aps-total-score"></span> / 10 <?php _e('based on your selection', 'aps-text'); ?>
				</li>
				<li>
					<input type="hidden" name="action" value="aps-review" />
					<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('aps-review'); ?>" />
					<input type="hidden" name="pid" value="<?php echo $post->ID; ?>" />
					<input type="submit" class="aps-button aps-btn-skin alignright" name="add-review" value="<?php _e('Add Review', 'aps-text'); ?>" />
				</li>
			</ul>
		</form>
	<?php } else { ?>
		<h3><?php _e( 'Sorry, reviews are closed for this device.', 'aps-text' ); ?></h3>
	<?php }