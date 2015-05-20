<?php if (!defined('APS_VER')) exit('restricted access');
/*
 * @package WordPress
 * @subpackage APS Products
 */
	// build APS settings page
	function build_aps_settings_page() {
		$aps_nonce = wp_create_nonce('aps_nonce'); ?>
		<h2><span class="dashicons dashicons-admin-settings"></span> <?php echo APS_NAME .' ' .__('Settings', 'aps-text'); ?></h2>
		<div class="wrap aps-wrap">
			<div id="aps-settings-page">
				<ul class="aps-data-tabs">
					<li id="design-tab" data-tab="#tab-design" class="active"><?php _e('Design', 'aps-text'); ?></li>
					<li id="settings-tab" data-tab="#tab-settings"><?php _e('Settings', 'aps-text'); ?></li>
					<li id="features-tab" data-tab="#tab-features"><?php _e('Features', 'aps-text'); ?></li>
					<li id="rating-bars-tab" data-tab="#tab-rating-bars"><?php _e('Rating Bars', 'aps-text'); ?></li>
					<li id="tabs-tab" data-tab="#tab-tabs"><?php _e('Tabs', 'aps-text'); ?></li>
					<li id="zoom-tab" data-tab="#tab-zoom"><?php _e('Zoom', 'aps-text'); ?></li>
					<li id="gallery-tab" data-tab="#tab-gallery"><?php _e('Gallery', 'aps-text'); ?></li>
					<li id="affiliate-tab" data-tab="#tab-affiliate"><?php _e('Affiliates', 'aps-text'); ?></li>
				</ul>
				<div class="aps-tabs-container">
					<div id="tab-design" class="aps-tab-content">
						<p>Configure Design settings here, select container width, choose a skin from our pre-built skins or create your own by using color pickers belw, select border or box shadow, select where to display content (left or right).</p>
						<form id="aps-design" class="aps-form" action="#" method="post">
							<ul>
								<?php // get saved aps design settings
								$design = get_aps_design();
								
								// get design input fields
								$design_fields = aps_design_fields();
								foreach ($design_fields as $d_key => $d_field) { ?>
									<li>
										<div class="aps-col-1">
											<label for="aps-design-<?php echo $d_key; ?>"><?php echo $d_field['label']; ?></label>
										</div>
										<div class="aps-col-5">
											<?php // switch input types
											switch ($d_field['type']) {
												case 'select' : ?>
													<select class="aps-select-box" name="aps-settings[<?php echo $d_key; ?>]" id="aps-design-<?php echo $d_key; ?>">
														<?php foreach ($d_field['options'] as $ds_key => $ds_val) { ?>
															<option value="<?php echo $ds_key; ?>"<?php if ($design[$d_key] == $ds_key) echo ' selected="selected"'; ?>><?php echo $ds_val; ?></option>
														<?php } ?>
													</select>
												<?php break;
												case 'radio' : ?>
													<div class="aps-radio-options">
														<?php foreach ($d_field['options'] as $ds_key => $ds_val) { ?>
															<label>
																<span class="aps-rd-box">
																	<input type="radio" name="aps-settings[<?php echo $d_key; ?>]"<?php if ($design[$d_key] == $ds_key) echo ' checked="checked"'; ?> value="<?php echo $ds_key; ?>" />
																	<?php echo $ds_val; ?>
																</span>
																<img class="aps-rd-img" src="<?php echo APS_URL .'img/' .$ds_key; ?>.png" alt="<?php echo $ds_val; ?>" />
															</label><br />
														<?php } ?>
													</div>
												<?php break;
												case 'color' : ?>
													<input type="text" class="color-pick" name="aps-settings[<?php echo $d_key; ?>]" value="<?php if ($design[$d_key]) echo $design[$d_key]; ?>" />
												<?php break;
											} ?>
											
											<br /><span class="aps-opt-info"><?php echo $d_field['desc']; ?></span>
										</div>
									</li>
								<?php } ?>
							</ul>
							<button class="button alignleft reset-default" data-sec="aps-design" data-nonce="<?php echo $aps_nonce; ?>"><?php _e('Reset Default', 'aps-text'); ?></button>
							<input type="hidden" name="action" value="aps-plugin" />
							<input type="hidden" name="aps-section" value="aps-design" />
							<input type="hidden" name="aps-nonce" value="<?php echo $aps_nonce; ?>" />
							<input type="submit" class="button-primary alignright" name="design-submit" value="<?php _e('Save Changes', 'aps-text'); ?>" />
						</form>
					</div>
					
					<div id="tab-settings" class="aps-tab-content">
						<p>Select main settings for APS Plugin, here you can change products main index page, index page main heading, search archive main heading, brands archive main heading etc.</p>
						<form id="aps-settings" class="aps-form" action="#" method="post">
							<ul>
								<?php // get saved main settings
								$settings = get_aps_settings();
								
								// get settings input fields
								$settings_fields = aps_main_settings_fields();
								foreach ($settings_fields as $s_key => $s_field) { ?>
									<li>
										<div class="aps-col-1">
											<label for="aps-main-<?php echo $s_key; ?>"><?php echo $s_field['label']; ?></label>
										</div>
										<div class="aps-col-5">
											<?php // switch input types
											switch ($s_field['type']) {
												case 'select' : ?>
													<select class="aps-select-box" name="aps-settings[<?php echo $s_key; ?>]" id="aps-main-<?php echo $s_key; ?>">
														<?php foreach ($s_field['options'] as $st_key => $st_val) { ?>
															<option value="<?php echo $st_key; ?>"<?php if ($settings[$s_key] == $st_key) echo ' selected="selected"'; ?>><?php echo $st_val; ?></option>
														<?php } ?>
													</select>
												<?php break;
												case 'text' : ?>
													<input type="text" class="aps-text-input" name="aps-settings[<?php echo $s_key; ?>]" id="aps-main-<?php echo $s_key; ?>" value="<?php echo $settings[$s_key]; ?>" />
												<?php break;
												case 'textarea' : ?>
													<textarea class="aps-textarea" name="aps-settings[<?php echo $s_key; ?>]" id="aps-main-<?php echo $s_key; ?>" rows="3"><?php echo $settings[$s_key]; ?></textarea>
												<?php break;
											} ?>
											
											<br /><span class="aps-opt-info"><?php echo $s_field['desc']; ?></span>
										</div>
									</li>
								<?php } ?>
							</ul>
							<button class="button alignleft reset-default" data-sec="aps-settings" data-nonce="<?php echo $aps_nonce; ?>"><?php _e('Reset Default', 'aps-text'); ?></button>
							<input type="hidden" name="action" value="aps-plugin" />
							<input type="hidden" name="aps-section" value="aps-settings" />
							<input type="hidden" name="aps-nonce" value="<?php echo $aps_nonce; ?>" />
							<input type="submit" class="button-primary alignright" name="settings-submit" value="<?php _e('Save Changes', 'aps-text'); ?>" />
						</form>
					</div>
					
					<div id="tab-features" class="aps-tab-content">
						<p>Change the title and icons of products 6 main features displayed in the front of product image in single product view template, after editing press save changes button to save the settings.</p>
						<form id="aps-features" class="aps-form" action="#" method="post">
							<ul class="aps-fields-list">
								<?php // make an array of main features
									$main_features = get_aps_features();
									$icons = get_aps_icons();
									foreach ($main_features as $feature_key => $feature) { ?>
									<li class="aps-field-box">
										<div class="aps-col-3">
											<label><?php echo ucfirst($feature_key); ?> <?php _e('Title', 'aps-text'); ?></label>
											<input type="text" class="aps-text-input" name="aps-settings[<?php echo $feature_key; ?>][name]" value="<?php echo $feature['name']; ?>" />
										</div>
										<div class="aps-col-2">
											<label><?php echo ucfirst($feature_key); ?> <?php _e('Icon', 'aps-text'); ?></label>
											<select class="aps-select-box" name="aps-settings[<?php echo $feature_key; ?>][icon]">
												<?php foreach ($icons as $key => $val) { ?>
													<option value="<?php echo $key; ?>" class="aps-icon-<?php echo $key; ?>"<?php if ($feature['icon'] == $key) echo ' selected="selected"'; ?>> <?php echo $val; ?></option>
												<?php } ?>
											</select>
										</div>
									</li>
								<?php } ?>
							</ul>
							<button class="button alignleft reset-default" data-sec="aps-features" data-nonce="<?php echo $aps_nonce; ?>"><?php _e('Reset Default', 'aps-text'); ?></button>
							<input type="hidden" name="action" value="aps-plugin" />
							<input type="hidden" name="aps-section" value="aps-features" />
							<input type="hidden" name="aps-nonce" value="<?php echo $aps_nonce; ?>" />
							<input type="submit" class="button-primary alignright" name="features-submit" value="<?php _e('Save Changes', 'aps-text'); ?>" />
						</form>
					</div>
					
					<div id="tab-rating-bars" class="aps-tab-content">
						<p>Customize the title, default value and tooltip information of rating bars, also you can change display order of rating bars by moving them up / down, press save changes button to save settings, or press reset default button to restore default rating bars.</p>
						<form id="aps-bars" class="aps-form" action="#" method="post">
							<ul class="aps-sortable aps-fields-list aps-bars-list">
								<?php // get saved tabs settings
								$rating_bars = get_aps_rating_bars(); ?>
							</ul>
							<div class="hidden-bar">
								<div class="aps-box-inside">
									<span class="tb-title"><span class="dashicons dashicons-menu"></span></span>
									<div class="aps-col-1">
										<label><?php _e('Rating Title', 'aps-text'); ?></label>
										<input class="bar-label aps-text-input" type="text" name="aps-dynamic" value="" />
									</div>
									<div class="aps-col-1">
										<label><?php _e('Default Value', 'aps-text'); ?></label>
										<select class="bar-value aps-select-box" name="aps-dynamic">
											<?php for ($i = 1; $i < 11; $i++) { ?>
												<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="aps-col-3">
										<label><?php _e('Info (tooltip)', 'aps-text'); ?></label>
										<textarea class="bar-info aps-textarea" name="aps-dynamic" rows="2"></textarea>
									</div>
									<a class="delete-bar aps-btn-del" href=""><span class="dashicons dashicons-dismiss"></span></a>
								</div>
							</div>
							<div class="aps-tabset">
								<a href="#" class="add-bar aps-btn aps-btn-green"><i class="aps-icon-plus"></i> <?php _e('Add Bar', 'aps-text'); ?></a>
							</div>
							<button class="button alignleft reset-default" data-sec="aps-rating-bars" data-nonce="<?php echo $aps_nonce; ?>"><?php _e('Reset Default', 'aps-text'); ?></button>
							<input type="hidden" name="action" value="aps-plugin" />
							<input type="hidden" name="aps-section" value="aps-rating-bars" />
							<input type="hidden" name="aps-nonce" value="<?php echo $aps_nonce; ?>" />
							<input type="submit" class="button-primary alignright" name="rating-bars-submit" value="<?php _e('Save Changes', 'aps-text'); ?>" />
						</form>
					</div>
					
					<div id="tab-tabs" class="aps-tab-content">
						<p>Customize the title, content, display setting of tabs, also you can change display order of tabs by moving them up / down, press save changes button to save the order. <br />You can add custom tabs to display more data of a product, you can add custom tabs content by editing a product post.</p>
						<form id="aps-tabs" class="aps-form" action="#" method="post">
							<ul class="aps-sortable aps-fields-list aps-tabs-list">
								<?php // get saved tabs settings
								$tabs = get_aps_tabs();
								$contents = array('overview', 'specs', 'reviews', 'gallery', 'videos', 'offers', 'custom1', 'custom2', 'custom3'); ?>
							</ul>
							<div class="hidden-data">
								<div class="aps-box-inside">
									<span class="tb-title"><span class="dashicons dashicons-menu"></span></span>
									<div class="aps-col-3">
										<label><?php _e('Tab Title', 'aps-text'); ?></label>
										<input class="tab-name aps-text-input" type="text" name="aps-dynamic" value="" />
									</div>
									<div class="aps-col-1">
										<label><?php _e('Content', 'aps-text'); ?></label>
										<select class="tab-contents aps-select-box" name="aps-dynamic">
											<?php foreach ($contents as $opt) { ?>
												<option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="aps-col-1">
										<label><?php _e('Display', 'aps-text'); ?></label>
										<input class="tab-display aps-checkbox" type="checkbox" name="aps-dynamic" value="yes" />
									</div>
									<a class="delete-tabs aps-btn-del" href=""><span class="dashicons dashicons-dismiss"></span></a>
								</div>
							</div>
							<div class="aps-tabset">
								<a href="#" class="add-tabs aps-btn aps-btn-green"><i class="aps-icon-plus"></i><?php _e('Add Tab', 'aps-text'); ?></a>
							</div>
							<button class="button alignleft reset-default" data-sec="aps-tabs" data-nonce="<?php echo $aps_nonce; ?>"><?php _e('Reset Default', 'aps-text'); ?></button>
							<input type="hidden" name="action" value="aps-plugin" />
							<input type="hidden" name="aps-section" value="aps-tabs" />
							<input type="hidden" name="aps-nonce" value="<?php echo $aps_nonce; ?>" />
							<input type="submit" class="button-primary alignright" name="tabs-submit" value="<?php _e('Save Changes', 'aps-text'); ?>" />
						</form>
					</div>
					
					<div id="tab-zoom" class="aps-tab-content">
						<p>Here you can manage main image zoom settings, press save changes button to save the settings.</p>
						<form id="aps-zoom" class="aps-form" action="#" method="post">
							<ul>
								<?php // get saved zoom settings
								$zoom = get_option('aps-zoom');
								
								// get zoom input fields
								$zoom_fields = aps_zoom_settings_fields();
								foreach ($zoom_fields as $z_key => $z_field) { ?>
									<li>
										<div class="aps-col-1">
											<label for="aps-zoom-<?php echo $z_key; ?>"><?php echo $z_field['label']; ?></label>
										</div>
										<div class="aps-col-5">
											<?php // switch input types
											switch ($z_field['type']) {
												case 'select' : ?>
													<select class="aps-select-box" name="aps-settings[<?php echo $z_key; ?>]" id="aps-zoom-<?php echo $z_key; ?>">
														<?php foreach ($z_field['options'] as $zf_key => $zf_val) { ?>
															<option value="<?php echo $zf_key; ?>"<?php if ($zoom[$z_key] == $zf_key) echo ' selected="selected"'; ?>><?php echo $zf_val; ?></option>
														<?php } ?>
													</select>
												<?php break;
												case 'text' : ?>
													<input type="text" class="aps-text-input" name="aps-settings[<?php echo $z_key; ?>]" id="aps-zoom-<?php echo $z_key; ?>" value="<?php echo $zoom[$z_key]; ?>" />
												<?php break;
											} ?>
											
											<br /><span class="aps-opt-info"><?php echo $z_field['desc']; ?></span>
										</div>
									</li>
								<?php } ?>
							</ul>
							<button class="button alignleft reset-default" data-sec="aps-zoom" data-nonce="<?php echo $aps_nonce; ?>"><?php _e('Reset Default', 'aps-text'); ?></button>
							<input type="hidden" name="action" value="aps-plugin" />
							<input type="hidden" name="aps-section" value="aps-zoom" />
							<input type="hidden" name="aps-nonce" value="<?php echo $aps_nonce; ?>" />
							<input type="submit" class="button-primary alignright" name="zoom-submit" value="<?php _e('Save Changes', 'aps-text'); ?>" />
						</form>
					</div>
					
					<div id="tab-gallery" class="aps-tab-content">
						<p>Here you can manage gallery lightbox settings, press save changes button to save the settings.</p>
						<form id="aps-gallery" class="aps-form" action="#" method="post">
							<ul>
								<?php // get saved gallery settings
								$gallery = get_option('aps-gallery');
								
								// make input fields
								$gallery_fields = aps_gallery_settings_fields();
								foreach ($gallery_fields as $g_key => $g_field) { ?>
									<li>
										<div class="aps-col-1">
											<label for="aps-gallery-<?php echo $g_key; ?>"><?php echo $g_field['label']; ?></label>
										</div>
										<div class="aps-col-5">
											<select class="aps-select-box" name="aps-settings[<?php echo $g_key; ?>]" id="aps-gallery-<?php echo $g_key; ?>">
												<?php foreach ($g_field['options'] as $gf_key => $gf_val) { ?>
													<option value="<?php echo $gf_key; ?>"<?php if ($gallery[$g_key] == $gf_key) echo ' selected="selected"'; ?>><?php echo $gf_val; ?></option>
												<?php } ?>
											</select>
											<br /><span class="aps-opt-info"><?php echo $g_field['desc']; ?></span>
										</div>
									</li>
								<?php } ?>
							</ul>
							<button class="button alignleft reset-default" data-sec="aps-gallery" data-nonce="<?php echo $aps_nonce; ?>"><?php _e('Reset Default', 'aps-text'); ?></button>
							<input type="hidden" name="action" value="aps-plugin" />
							<input type="hidden" name="aps-section" value="aps-gallery" />
							<input type="hidden" name="aps-nonce" value="<?php echo $aps_nonce; ?>" />
							<input type="submit" class="button-primary alignright" name="gallery-submit" value="<?php _e('Save Changes', 'aps-text'); ?>" />
						</form>
					</div>
					
					<div id="tab-affiliate" class="aps-tab-content">
						<p>Here you can manage affiliate settings, enter a store name e.g(Amazon) upload a logo image (100 by 60px), press save changes button to save the settings.</p>
						<form id="aps-zoom" class="aps-form" action="#" method="post">
							<?php // get saved affiliate settings
							$affiliates = get_aps_affiliates(); ?>
							<ul class="aps-sortable aps-fields-list aps-affs-list">
							</ul>
							<div class="hidden-data aps-aff-data">
								<div class="aps-box-inside">
									<span class="tb-title"><span class="dashicons dashicons-menu"></span></span>
									<div class="aps-col-1">
										<label><?php _e('Store Name', 'aps-text'); ?></label>
										<input class="aff-name aps-text-input" type="text" name="aps-dynamic" value="" />
									</div>
									<div class="aps-col-3">
										<label><?php _e('Logo URL', 'aps-text'); ?></label>
										<input class="aff-logo aps-text-input" type="text" name="aps-dynamic" value="" />
									</div>
									<div class="aps-col-1">
										<label><?php _e('Select / Upload', 'aps-text'); ?></label>
										<a class="button aps-media-upload" href=""><?php _e('Logo Image', 'aps-text'); ?></a>
									</div>
									<a class="delete-aff aps-btn-del" href=""><span class="dashicons dashicons-dismiss"></span></a>
								</div>
							</div>
							<div class="aps-tabset">
								<a href="#" class="add-aff aps-btn aps-btn-green"><i class="aps-icon-plus"></i><?php _e('Add Store', 'aps-text'); ?></a>
							</div>
							<button class="button alignleft reset-default" data-sec="aps-affiliates" data-nonce="<?php echo $aps_nonce; ?>"><?php _e('Reset Default', 'aps-text'); ?></button>
							<input type="hidden" name="action" value="aps-plugin" />
							<input type="hidden" name="aps-section" value="aps-affiliates" />
							<input type="hidden" name="aps-nonce" value="<?php echo $aps_nonce; ?>" />
							<input type="submit" class="button-primary alignright" name="affiliates-submit" value="<?php _e('Save Changes', 'aps-text'); ?>" />
						</form>
					</div>
				</div>
				<script type="text/javascript">
				(function($) {
					// tabs
					tabs_data = <?php echo json_encode($tabs); ?>
					
					if (typeof(tabs_data) != 'undefined') {
						var tabs_html = $(".hidden-data").html(), counter = 0;
						
						function aps_tabs_html(selection, td_val) {
							$(selection).find(":input").each(function() {
								var thisi = $(this);
								if (thisi.hasClass("tab-name")) {
									thisi.val(td_val.name).attr('name', 'aps-settings['+counter+'][name]');
								} else if (thisi.hasClass("tab-contents")) {
									thisi.val(td_val.content).attr('name', 'aps-settings['+counter+'][content]');
								} else if (thisi.hasClass("tab-display")) {
									if (td_val.display == 'yes') {
										thisi.prop('checked', true);
									}
									thisi.attr('name', 'aps-settings['+counter+'][display]');
								}
							});
							counter++;
						}
						
						$(document).on("click", "a.delete-tabs", function(e) {
							$(this).parents(".tabs-box").remove();
							if (!$(".tabs-box").length) counter = 0;
							e.preventDefault();
						});
						
						$(document).on("click", "a.add-tabs", function(e) {
							$(".aps-tabs-list").append('<li class="aps-field-box tabs-box tabset-'+counter+'">'+tabs_html+'</li>');
							var values = {name: "Custom Tab", content: "custom1", display: "yes"};
							aps_tabs_html(".tabset-"+counter, values);
							e.preventDefault();
						});
						
						$.each(tabs_data, function(k, v) {
							$(".aps-tabs-list").append('<li class="aps-field-box tabs-box tabset-'+counter+'">'+tabs_html+'</li>');
							aps_tabs_html(".tabset-"+counter, v);
						});
					}
					
					// affiliate
					affs_data = <?php echo json_encode($affiliates); ?>
					
					if (typeof(affs_data) != 'undefined') {
						var affs_html = $(".aps-aff-data").html(), counter = 0;
						
						function aps_affs_html(selection, td_val) {
							$(selection).find(":input").each(function() {
								var thisi = $(this);
								if (thisi.hasClass("aff-name")) {
									thisi.val(td_val.name).attr('name', 'aps-settings['+counter+'][name]');
								} else if (thisi.hasClass("aff-logo")) {
									thisi.val(td_val.logo).attr('name', 'aps-settings['+counter+'][logo]');
								}
							});
							counter++;
						}
						
						$(document).on("click", "a.delete-aff", function(e) {
							$(this).parents(".tabs-box").remove();
							if (!$(".tabs-box").length) counter = 0;
							e.preventDefault();
						});
						
						$(document).on("click", "a.add-aff", function(e) {
							$(".aps-affs-list").append('<li class="aps-field-box tabs-box tabset-'+counter+'">'+affs_html+'</li>');
							var values = {name: "", logo: ""};
							aps_affs_html(".tabset-"+counter, values);
							e.preventDefault();
						});
						
						$.each(affs_data, function(k, v) {
							$(".aps-affs-list").append('<li class="aps-field-box tabs-box tabset-'+counter+'">'+affs_html+'</li>');
							aps_affs_html(".tabset-"+counter, v);
						});
					}
					
					// rating bars
					bars_data = <?php echo json_encode($rating_bars); ?>
					
					if (typeof(bars_data) != 'undefined') {
						var bars_html = $(".hidden-bar").html(), counter = 0;
						
						function aps_bars_html(selection, td_val) {
							$(selection).find(":input").each(function() {
								var thisi = $(this);
								if (thisi.hasClass("bar-label")) {
									thisi.val(td_val.label).attr('name', 'aps-settings['+counter+'][label]');
								} else if (thisi.hasClass("bar-value")) {
									thisi.val(td_val.value).attr('name', 'aps-settings['+counter+'][value]');
								} else if (thisi.hasClass("bar-info")) {
									thisi.val(td_val.info).attr('name', 'aps-settings['+counter+'][info]');
								}
							});
							counter++;
						}
						
						$(document).on("click", "a.delete-bar", function(e) {
							$(this).parents(".bars-box").remove();
							if (!$(".bars-box").length) counter = 0;
							e.preventDefault();
						});
						
						$(document).on("click", "a.add-bar", function(e) {
							$(".aps-bars-list").append('<li class="aps-field-box bars-box barset-'+counter+'">'+bars_html+'</li>');
							var values = {label: "", value: "5", info: ""};
							aps_bars_html(".barset-"+counter, values);
							e.preventDefault();
						});
						
						$.each(bars_data, function(k, v) {
							$(".aps-bars-list").append('<li class="aps-field-box bars-box barset-'+counter+'">'+bars_html+'</li>');
							aps_bars_html(".barset-"+counter, v);
						});
					}
				})(jQuery);
				
				jQuery(document).ready(function($) {
					// aps tabs
					var selected_tab = localStorage.getItem("selected-tab");
					
					$("ul.aps-data-tabs li").click(function(e) {
						$("ul.aps-data-tabs li").removeClass("active");
						$(this).addClass("active");
						$(".aps-tab-content").hide();
						var id = $(this).attr("id");
						var activeTab = $(this).data("tab");
						$(activeTab).fadeIn("slow");
						localStorage.setItem("selected-tab", id);
						e.preventDefault();
					});
					
					if (selected_tab) {
						$("ul.aps-data-tabs li#" + selected_tab).trigger("click");
					} else {
						$(".aps-tab-content:first").show();
					}
					
					// init group sortable order
					$(".aps-sortable").sortable({
						items: "li",
						opacity: 0.7
					});
					
					// init wp color picker
					$(".color-pick").wpColorPicker({
						hide: true,
						palettes: true
					});
					
					// submit form via ajax
					$(document).on("submit", ".aps-form", function(e) {
						var form = $(this),
						button = form.find(".button-primary"),
						formData = form.serialize();
						
						$.ajax({
							url: ajaxurl,
							type: "POST",
							data: formData,
							dataType: "json",
							beforeSend: function() {
								button.hide();
								button.after('<span class="loading alignright"></span>');
							},
							success: function(res) {
								display_success_msg(res.message);
							},
							complete: function() {
								form.find(".loading").remove();
								button.show();
							}
						});
						e.preventDefault();
					});
					
					// reset default data via ajax
					$(".reset-default").click(function(e) {
						var button = $(this),
						section = button.data("sec"),
						nonce = button.data("nonce");
						
						user_input = confirm("Are You sure you want to restore default settings?");
						if (user_input == true) {
							$.ajax({
								url: ajaxurl,
								type: "POST",
								data: {action: "aps-reset", section: section, nonce: nonce},
								dataType: "json",
								beforeSend: function() {
									button.hide();
									button.after('<span class="loading alignleft"></span>');
								},
								success: function(res) {
									if (res.success == true) {
										window.location.href = window.location.href;
									}
									display_success_msg(res.message);
								},
								complete: function() {
									button.next(".loading").remove();
									button.show();
								}
							});
						}
						e.preventDefault();
					});
					
					// media upload
					$(document).on("click", ".aps-media-upload", function(e) {
						var logo_input = $(this).parents(".aps-box-inside").find(".aff-logo");
						frame = wp.media({
							title : "<?php _e('Select Logo Image', 'aps-text'); ?>",
							multiple: false,
							library : { type : "image"},
							button : { text : "<?php _e('Add Image', 'aps-text'); ?>" },
						});
						frame.on("select", function() {
							selection = frame.state().get("selection");
							selection.each(function(image) {
								image_url = image.attributes.url;
								logo_input.val(image_url);
							});
						});
						frame.open();
						e.preventDefault();
					});
				});
				
				// display ajax response message
				function display_success_msg(msg) {
					msg_box = jQuery(".response-msg");
					msg_box.html(msg).fadeIn();
					setTimeout(function() {
						msg_box.fadeOut();
					}, 5000);
				}
				</script>
				<div class="response-msg"></div>
			</div>
			<div id="aps-settings-side">
				<?php aps_plugin_sidebar(); ?>
			</div>
		</div>
		<?php
	}
	
	// add an update notification to the WordPress Dashboard menu
	add_action('admin_menu', 'aps_update_notifier_menu');

	function aps_update_notifier_menu() {  
		
		// check version
		$version = get_aps_latest_version();
		
		// Compare current plugin version
		if ($version > APS_VER) {
			add_dashboard_page( APS_NAME .' Plugin Updates', APS_NAME .' <span class="update-plugins count-1"><span class="update-count">1</span></span>', 'administrator', 'aps-update-notifier', 'aps_update_notifier');
		}	
	}

	// add an update notification to the WordPress 3.1+ Admin Bar
	add_action( 'admin_bar_menu', 'aps_update_notifier_bar_menu', 1000 );

	function aps_update_notifier_bar_menu() {
		global $wp_admin_bar, $wpdb;
		
		// display notification if current user is an administrator
		if ( is_super_admin() || is_admin_bar_showing() ) {
			
			// get latest version
			$version = get_aps_latest_version();
			if ( is_admin() ) {
				
				// Compare current plugin version
				if ($version > APS_VER) {
					$wp_admin_bar->add_menu( array( 'id' => 'plugin_update_notifier', 'title' => '<span>' .APS_NAME .' <span id="ab-updates">' .__('New Updates', 'aps-text') .'</span></span>', 'href' => get_admin_url() .'index.php?page=aps-update-notifier' ) );
				}
			}
		}
	}

	// get latest version info
	function get_aps_latest_version() {
		return ($ver = get_option('aps-latest-version')) ? $ver : APS_VER;
	}

	// build update notifier page
	function aps_update_notifier() { ?>
		<style>
			.update-nag { display: none; }
			#instructions {max-width: 800px;}
			h3.title {margin: 30px 0 0; padding: 30px 0 10px; border-top: 1px solid #ddd;}
		</style>

		<div class="wrap">
			<h2><?php echo APS_NAME .__(' Plugin Updates', 'aps-text'); ?> <span class="dashicons dashicons-admin-settings"></span></h2>
			<div id="message" class="updated below-h2"><p><strong>There is a new version of the Arena Products Store plugin is available.</strong> You have version <?php echo APS_VER; ?> installed. Update to version <?php echo get_aps_latest_version(); ?></p></div>
			
			<div id="instructions">
				<h3>Download and Update Instructions</h3>
				<p><strong>Please note:</strong> make a <strong>backup</strong> of the Plugin inside your WordPress installation folder <strong>/wp-content/plugins/aps-products/</strong></p>
				<p>To update the plugin, login to <a href="http://www.codecanyon.net/">CodeCanyon</a>, head over to your <strong>downloads</strong> section and re-download the plugin like you did when you bought it.</p>
				<p>Extract the zip's contents, look for the extracted plugin folder, and after you have all the new files upload them using FTP to the <strong>/wp-content/plugins/aps-products/</strong> directory overwriting the old ones (this is why it's important to backup any changes you've made to the plugin files).</p>
				<p>If you didn't make any changes to the plugin files, you are free to overwrite them with the new ones without the risk of losing any plugins settings, and backwards compatibility is guaranteed.</p>
			</div>
			
			<h3 class="title">Changelog</h3>
			<?php echo get_option('aps-latest-changes'); ?>
		</div>
		<?php
	}
