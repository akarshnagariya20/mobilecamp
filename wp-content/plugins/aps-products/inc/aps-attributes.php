<?php if (!defined('APS_VER')) exit('restricted access');
/*
 * @package WordPress
 * @subpackage APS Products
*/
	// build APS groups page
	function build_aps_groups_page() {
		$aps_nonce = wp_create_nonce('aps_nonce'); ?>
		<h2><span class="dashicons dashicons-admin-settings"></span> <?php _e('APS Attributes Groups', 'aps-text'); ?></h2>
		<div class="wrap aps-wrap">
			<div id="aps-settings-page">
				<div class="aps-tabs-container">
					<div class="aps-content">
						<p>Add / remove / edit attributes groups, also you can change display order of groups by moving them up / down, press save changes button to save settings.</p>
						<form id="aps-tabs" class="aps-form" action="#" method="post">
							<ul class="aps-sortable aps-fields-list">
								<?php // get saved groups
								$groups = get_aps_groups();
								$icons = get_aps_icons(); ?>
							</ul>
							<div class="aps-tabset">
								<a href="#" class="aps-btn aps-btn-green add-tabs"><i class="aps-icon-plus"></i> <?php _e('Add Group', 'aps-text'); ?></a>
							</div>
							<button class="button alignleft reset-default" data-sec="aps-groups" data-nonce="<?php echo $aps_nonce; ?>"><?php _e('Reset Default', 'aps-text'); ?></button>
							<input type="hidden" name="aps-groups" id="aps-groups" value="<?php echo get_option('aps-num-groups'); ?>" />
							<input type="hidden" name="action" value="aps-plugin" />
							<input type="hidden" name="aps-section" value="aps-groups" />
							<input type="hidden" name="aps-nonce" value="<?php echo $aps_nonce; ?>" />
							<input type="submit" class="button-primary alignright" name="groups-submit" value="<?php _e('Save Changes', 'aps-text'); ?>" />
						</form>
						<div class="hidden-data">
							<div class="aps-box-inside">
								<span class="tb-title"><span class="dashicons dashicons-menu"></span></span>
								<div class="aps-col-3">
									<label><?php _e('Group Title', 'aps-text'); ?></label>
									<input class="tab-name aps-text-input" type="text" name="aps-dynamic" value="" />
								</div>
								<div class="aps-col-1">
									<label><?php _e('Icon', 'aps-text'); ?></label>
									<select class="tab-contents aps-select-box" name="aps-dynamic">
										<?php foreach ($icons as $key => $val) { ?>
											<option value="<?php echo $key; ?>" class="aps-icon-<?php echo $key; ?>"> <?php echo $val; ?></option>
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
					</div>
				</div>
			</div>
			<div id="aps-settings-side">
				<?php aps_plugin_sidebar(); ?>
			</div>
			<script type="text/javascript">
			(function($) {
				groups_data = <?php echo json_encode($groups); ?>;
				
				if (typeof(groups_data) != 'undefined') {
					var group_html = $(".hidden-data").html(), counter = 0;
					
					function aps_groups_html(selection, group, td_val) {
						$(selection).find(":input").each(function() {
							var thisi = $(this);
							if (thisi.hasClass("tab-name")) {
								thisi.val(td_val.name).attr('name', 'aps-settings['+group+'][name]');
							} else if (thisi.hasClass("tab-contents")) {
								thisi.val(td_val.icon).attr('name', 'aps-settings['+group+'][icon]');
							} else if (thisi.hasClass("tab-display")) {
								if (td_val.display == 'yes') {
									thisi.prop('checked', true);
								}
								thisi.attr('name', 'aps-settings['+group+'][display]');
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
						$(".aps-fields-list").append('<li class="aps-field-box tabs-box tabset-'+counter+'">'+group_html+'</li>');
						var values = {name: "Group", icon: "android", display: "yes"},
						group = parseInt($("#aps-groups").val()) + 1,
						group_key = 'group' + group;
						aps_groups_html(".tabset-"+counter, group_key, values);
						$("#aps-groups").val(group);
						e.preventDefault();
					});
					
					$.each(groups_data, function(k, v) {
						$(".aps-fields-list").append('<li class="aps-field-box tabs-box tabset-'+counter+'">'+group_html+'</li>');
						aps_groups_html(".tabset-"+counter, k, v);
					});
				}
			})(jQuery);
			jQuery(document).ready(function($) {
				// init group sortable order
				$(".aps-sortable").sortable({
					items: "li",
					opacity: 0.7
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
							if (res.success == true) {
								display_success_msg(res.message);
							}
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
					
					user_input = confirm("Are You sure you want to restore default Groups?");
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
		<?php
	}
	
	// build APS attributes page
	function build_aps_attributes_page() {
		// get saved groups
		$groups = get_aps_groups(); ?>
		<h2><span class="dashicons dashicons-admin-settings"></span> <?php _e('APS Products Attributes', 'aps-text'); ?></h2>
		<div class="wrap aps-wrap">
			<div id="aps-settings-page">
				<div class="aps-tabs-container">
					<div class="aps-content">
						<p>Add / remove / edit product attributes, also you can change display order of attributes by moving them up / down, press save changes button to save settings.</p>
						<?php if (!empty($groups)) {
							$li = 0; ?>
							<ul class="aps-data-pils">
								<?php foreach ($groups as $group_key => $group) { ?>
									<li data-pil="#aps-pil-<?php echo $group_key; ?>"<?php if ($li == 0) echo ' class="active"'; ?>><?php echo $group['name']; ?></li>
									<?php $li++;
								} ?>
							</ul>
							<form id="aps-tabs" class="aps-form" action="#" method="post">
								<div class="aps-pil-container">
									<?php // get saved attributes
									$attributes = get_aps_attributes();
									$aps_nonce = wp_create_nonce('aps_nonce');
									foreach ($groups as $group_key => $group) { ?>
										<div id="aps-pil-<?php echo $group_key; ?>" class="aps-pil-content">
											<h2><i class="aps-icon-<?php echo $group['icon']; ?>"></i> <?php echo $group['name']; ?></h2>
											<ul id="group-<?php echo $group_key; ?>" class="aps-sortable aps-fields-list aps-attr-sorting">
											</ul>
											<div class="aps-tabset">
												<a class="aps-btn aps-btn-green add-attr" href="#" data-group="<?php echo $group_key; ?>"><i class="aps-icon-plus"></i> <?php _e('Add Attribute', 'aps-text'); ?></a>
											</div>
										</div>
									<?php } ?>
								</div>
								<button class="button alignleft reset-default" data-sec="aps-attributes" data-nonce="<?php echo $aps_nonce; ?>"><?php _e('Reset Default', 'aps-text'); ?></button>
								<input type="hidden" name="action" value="aps-plugin" />
								<input type="hidden" name="aps-section" value="aps-attributes" />
								<input type="hidden" name="aps-nonce" value="<?php echo $aps_nonce; ?>" />
								<input type="submit" class="button-primary alignright" name="attributes-submit" value="<?php _e('Save Changes', 'aps-text'); ?>" />
							</form>
							<div class="hidden-data">
								<div class="aps-box-inside">
									<span class="tb-title"><span class="dashicons dashicons-menu"></span></span>
									<div class="aps-col-3">
										<label><?php _e('Attribute Title', 'aps-text'); ?></label>
										<input class="attr-name aps-text-input" type="text" name="aps-dynamic" value="" />
									</div>
									<div class="aps-col-1">
										<label><?php _e('Input Type', 'aps-text'); ?></label>
										<select class="attr-type aps-select-box" name="aps-dynamic">
											<option value="text"> <?php _e('Text input', 'aps-text'); ?></option>
											<option value="textarea"> <?php _e('Textarea', 'aps-text'); ?></option>
											<option value="select"> <?php _e('Select Box', 'aps-text'); ?></option>
											<option value="check"> <?php _e('Check Box', 'aps-text'); ?></option>
											<option value="date"> <?php _e('Date Picker', 'aps-text'); ?></option>
										</select>
									</div>
									<div class="aps-col-1">
										<label><?php _e('Display', 'aps-text'); ?></label>
										<input class="attr-display aps-checkbox" type="checkbox" name="aps-dynamic" value="yes" />
									</div>
									<div class="aps-col-6 tab-full-box">
										<label><?php _e('Tooltip Info', 'aps-text'); ?></label>
										<textarea class="attr-tooltip aps-textarea" name="aps-dynamic"></textarea>
									</div>
									<a class="delete-tabs aps-btn-del" href=""><span class="dashicons dashicons-dismiss"></span></a>
								</div>
							</div>
							
							<div class="option-data">
								<a class="add-option" href="#"><?php _e('Add Option', 'aps-text'); ?></a>
							</div>
						<?php } else { ?>
							<h2><?php _e('No Groups', 'aps-text'); ?></h2>
							<p>Before you continue to add new attributes for your products specifications you need to add a few groups from <a href="<?php echo admin_url('edit.php?post_type=aps-products&page=aps-groups'); ?>">APS Groups</a> page.</p>
						<?php } ?>
					</div>
				</div>
			</div>
			<div id="aps-settings-side">
				<?php aps_plugin_sidebar(); ?>
			</div>
			<script type="text/javascript">
			(function($) {
				attr_data = <?php echo json_encode($attributes); ?>;
				
				if (typeof(attr_data) != "undefined") {
					var attr_html = $(".hidden-data").html(), counter = 0;
					
					function aps_attr_html(selection, key, at_key, at_val) {
						$(selection).find(":input").each(function() {
							var thisi = $(this);
							if (thisi.hasClass("attr-name")) {
								thisi.val(at_val.name).attr('name', 'aps-settings['+key+']['+at_key+'][name]');
							} else if (thisi.hasClass("attr-type")) {
								thisi.val(at_val.type).attr('name', 'aps-settings['+key+']['+at_key+'][type]').data('key', 'aps-settings['+key+']['+at_key+']');
								if (typeof(at_val.options) != "undefined") {
									var options = "",
									option_data = $(".option-data").html();
									$(at_val.options).each(function(k,v) {
										options += '<div class="tab-option-box"><input type="text" class="tab-contents" name="aps-settings['+key+']['+at_key+'][options][]" value="'+v+'" /><a class="delete-option" href="#"><i class="aps-icon-cancel"></i></div>';
									});
									$(selection).find(".tab-full-box").before('<div class="aps-col-6 tab-options-box" data-key="aps-settings['+key+']['+at_key+']">'+options+option_data+'</div>');
								}
							} else if (thisi.hasClass("attr-tooltip")) {
								thisi.val(at_val['info']);
								thisi.attr('name', 'aps-settings['+key+']['+at_key+'][info]');
							} else if (thisi.hasClass("attr-display")) {
								if (at_val['display'] == 'yes') {
									thisi.prop('checked', true);
								}
								thisi.attr('name', 'aps-settings['+key+']['+at_key+'][display]');
							}
						});
						counter++;
					}
					
					$(document).on("click", "a.delete-tabs", function(e) {
						$(this).parents(".tabs-box").remove();
						if (!$(".tabs-box").length) counter = 0;
						e.preventDefault();
					});
					
					$(document).on("click", "a.add-attr", function(e) {
						var group_id = $(this).data("group");
						$("#group-" + group_id).append('<li class="aps-field-box tabs-box tabset-'+counter+'" data-group="'+group_id+'">'+attr_html+'</li>');
						var values = {name:"Title", type:"text", display:"yes", info:""};
						aps_attr_html(".tabset-"+counter, group_id, counter, values);
						e.preventDefault();
					});
					
					$.each(attr_data, function(key, val) {
						$.each(val, function(at_key, at_val) {
							$("#group-" + key).append('<li class="aps-field-box tabs-box tabset-'+counter+'" data-group="'+key+'">'+attr_html+'</li>');
							aps_attr_html(".tabset-"+counter, key, at_key, at_val);
						});
					});
					
					// on change attribute title
					$(document).on("change keyup input propertychange", ".attr-name", function(e) {
						var tab_box = $(this).parents(".tabs-box"),
						key = tab_box.data("group"),
						input_val = $(this).val().toLowerCase();
						at_key = input_val.replace(/[^A-Z0-9]+/ig, "-");
						
						$(tab_box).find(":input").each(function() {
							var thisi = $(this);
							if (thisi.hasClass("attr-name")) {
								thisi.attr('name', 'aps-settings['+key+']['+at_key+'][name]');
							} else if (thisi.hasClass("attr-type")) {
								thisi.attr('name', 'aps-settings['+key+']['+at_key+'][type]').data('key', 'aps-settings['+key+']['+at_key+']');
							} else if (thisi.hasClass("tab-contents")) {
								thisi.attr('name', 'aps-settings['+key+']['+at_key+'][options][]');
							} else if (thisi.hasClass("attr-tooltip")) {
								thisi.attr('name', 'aps-settings['+key+']['+at_key+'][info]');
							} else if (thisi.hasClass("attr-display")) {
								thisi.attr('name', 'aps-settings['+key+']['+at_key+'][display]');
							}
						});
					});
					
					// on change attribute input type
					$(document).on("change", ".attr-type", function(e) {
						var input_type = $(this).val(),
						tab_box = $(this).parents(".tabs-box"),
						option_data = $(".option-data").html();
						
						if (input_type == "select") {
							if (tab_box.find(".tab-options-box").length == 0) {
								var key = $(this).data("key");
								tab_box.find(".tab-full-box").before('<div class="aps-col-6 tab-options-box" data-key="'+key+'">'+option_data+'</div>');
							} else {
								tab_box.find(".tab-options-box").slideDown();
							}
						} else {
							tab_box.find(".tab-options-box").slideUp();
						}
					});
					
					// on add option
					$(document).on("click", ".add-option", function(e) {
						var key = $(this).parents(".tab-options-box").data("key");
						$(this).before('<div class="tab-option-box"><input type="text" class="tab-contents" name="'+key+'[options][]" value="" /><a class="delete-option" href="#"><i class="aps-icon-cancel"></i></a></div>');
						e.preventDefault();
					});
					
					// on remove option
					$(document).on("click", ".delete-option", function(e) {
						$(this).parent(".tab-option-box").remove();
						e.preventDefault();
					});
				}
			})(jQuery);
			jQuery(document).ready(function($) {
				// groups tabs
				$(".aps-pil-content:first").show();
				$("ul.aps-data-pils li").click(function(e) {
					$("ul.aps-data-pils li").removeClass("active");
					$(this).addClass("active");
					$(".aps-pil-content").hide();
					var activeTab = $(this).data("pil");
					$(activeTab).fadeIn("fast");
				});
				
				// init group sortable order
				$(".aps-sortable").sortable({
					items: "li",
					opacity: 0.7
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
							if (res.success == true) {
								display_success_msg(res.message);
							}
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
					
					user_input = confirm("Are You sure you want to restore default Attributes?");
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
		<?php
	}
	
	// build APS filters page
	function build_aps_filters_page() {
		// get saved filters
		$filters = get_aps_filters(); ?>
		<h2><span class="dashicons dashicons-admin-settings"></span> <?php _e('APS Products Filters', 'aps-text'); ?></h2>
		<div class="wrap aps-wrap">
			<div id="aps-settings-page">
				<div class="aps-tabs-container">
					<div class="aps-content">
						<p>Add / remove / edit product filters, change filter name and slug, don't change filter slug which has some terms defined, also you can change display order of filters by moving them up / down, press save changes button to save settings.</p>
						<form id="aps-tabs" class="aps-form" action="#" method="post">
							<?php // create aps nonce
							$aps_nonce = wp_create_nonce('aps_nonce'); ?>
							<div class="tabs-container">
								<ul class="aps-sortable aps-fields-list aps-attr-sorting">
								</ul>
								<div class="aps-tabset">
									<a class="aps-btn aps-btn-green add-filter" href="#"><i class="aps-icon-plus"></i> <?php _e('Add Filter', 'aps-text'); ?></a>
								</div>
							</div>
							<button class="button alignleft reset-default" data-sec="aps-filters" data-nonce="<?php echo $aps_nonce; ?>"><?php _e('Reset Default', 'aps-text'); ?></button>
							<input type="hidden" name="action" value="aps-plugin" />
							<input type="hidden" name="aps-section" value="aps-filters" />
							<input type="hidden" name="aps-nonce" value="<?php echo $aps_nonce; ?>" />
							<input type="submit" class="button-primary alignright" name="filters-submit" value="<?php _e('Save Changes', 'aps-text'); ?>" />
						</form>
						<div class="hidden-data">
							<div class="aps-box-inside">
								<span class="tb-title"><span class="dashicons dashicons-menu"></span></span>
								<div class="aps-col-3">
									<label><?php _e('Filter Name', 'aps-text'); ?></label>
									<input class="filter-name aps-text-input" type="text" name="aps-dynamic" value="" />
								</div>
								<div class="aps-col-1">
									<label><?php _e('Filter Slug', 'aps-text'); ?></label>
									<input class="filter-slug aps-text-input" type="text" name="aps-dynamic" value="" />
								</div>
								<div class="aps-col-1">
									<label><?php _e('Add / Edit / View', 'aps-text'); ?></label>
									<a class="button add-terms" href="#"><?php _e('Filter Terms', 'aps-text'); ?></a>
								</div>
								<a class="delete-tabs aps-btn-del" href=""><span class="dashicons dashicons-dismiss"></span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="aps-settings-side">
				<?php aps_plugin_sidebar(); ?>
			</div>
			<script type="text/javascript">
			(function($) {
				filters_data = <?php echo json_encode($filters); ?>;
				
				if (typeof(filters_data) != 'undefined') {
					var filter_html = $(".hidden-data").html(), counter = 0;
					
					function aps_filters_html(selection, td_val) {
						$(selection).find(":input").each(function() {
							var thisi = $(this);
							if (thisi.hasClass("filter-name")) {
								thisi.val(td_val.name).attr('name', 'aps-settings['+counter+'][name]');
							} else if (thisi.hasClass("filter-slug")) {
								thisi.val(td_val.slug).attr('name', 'aps-settings['+counter+'][slug]');
							}
						});
						
						if (td_val['slug'] != "") {
							var tax_link = "<?php echo admin_url('edit-tags.php?post_type=aps-products&taxonomy=aps-'); ?>"+td_val['slug'];
							$(selection).find("a.add-terms").attr("href", tax_link);
						}
						counter++;
					}
					
					$(document).on("click", "a.delete-tabs", function(e) {
						$(this).parents(".tabs-box").remove();
						if (!$(".tabs-box").length) counter = 0;
						e.preventDefault();
					});
					
					$(document).on("click", "a.add-filter", function(e) {
						$(".aps-attr-sorting").append('<li class="aps-field-box tabs-box tabset-'+counter+'">'+filter_html+'</li>');
						var values = {name: "Filter Name", slug: ""};
						aps_filters_html(".tabset-"+counter, values);
						e.preventDefault();
					});
					
					$.each(filters_data, function(k, v) {
						$(".aps-attr-sorting").append('<li class="aps-field-box tabs-box tabset-'+counter+'">'+filter_html+'</li>');
						aps_filters_html(".tabset-"+counter, v);
					});
				}
			})(jQuery);
			jQuery(document).ready(function($) {
				// init filters sortable order
				$(".aps-attr-sorting").sortable({
					items: "li",
					opacity: 0.7
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
							if (res.success == true) {
								display_success_msg(res.message);
							}
						},
						complete: function() {
							form.find(".loading").remove();
							button.show();
							window.location.href = window.location.href;
						}
					});
					e.preventDefault();
				});
				
				// reset default data via ajax
				$(".reset-default").click(function(e) {
					var button = $(this),
					section = button.data("sec"),
					nonce = button.data("nonce");
					
					user_input = confirm("Are You sure you want to restore default Filters?");
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
		<?php
	}
	
	// main design settings fields
	function aps_design_fields() {
		// get all pages
		$fields = array(
			'container' => array(
				'label' => __('Container Width', 'aps-text'),
				'type' => 'select',
				'options' => array('960' => '960 Pixels', '1200' => '1200 Pixels'),
				'desc' => __('Select container width that best fit with your theme.', 'aps-text')
			),
			'responsive' => array(
				'label' => __('Responsive Layout', 'aps-text'),
				'type' => 'select',
				'options' => array('1' => 'Enable', '0' => 'Disable'),
				'desc' => __('Enable / Disable responsive layout.', 'aps-text')
			),
			'content' => array(
				'label' => __('Content Display', 'aps-text'),
				'type' => 'select',
				'options' => array('left' => 'Left', 'right' => 'Right'),
				'desc' => __('Select content and sidebar positions.', 'aps-text')
			),
			'skin' => array(
				'label' => __('Skin (color theme)', 'aps-text'),
				'type' => 'radio',
				'options' => array(
					'skin-blue' => __('Blue Skin', 'aps-text'),
					'skin-light-blue' => __('Light Blue Skin', 'aps-text'),
					'skin-green' => __('Green Skin', 'aps-text'),
					'skin-sea-green' => __('Sea Green Skin', 'aps-text'),
					'skin-orange' => __('Orange Skin', 'aps-text'),
					'skin-red' => __('Red Skin', 'aps-text'),
					'skin-pink' => __('Pink Skin', 'aps-text'),
					'skin-purple' => __('Purple Skin', 'aps-text'),
					'skin-brown' => __('Brown Skin', 'aps-text'),
					'skin-custom' => __('Custom Skin Colors', 'aps-text')
				),
				'desc' => __('Select a skin that best fit with your theme or select custom to choose your own colors below.', 'aps-text')
			),
			'color1' => array(
				'label' => __('Custom Color 1', 'aps-text'),
				'type' => 'color',
				'desc' => __('Select a lighter skin color.', 'aps-text')
			),
			'color2' => array(
				'label' => __('Custom Color 2', 'aps-text'),
				'type' => 'color',
				'desc' => __('Select a skin color, a number of elements use this color as background.', 'aps-text')
			),
			'color3' => array(
				'label' => __('Custom Color 3', 'aps-text'),
				'type' => 'color',
				'desc' => __('Select a darker version of skin color.', 'aps-text')
			),
			'border' => array(
				'label' => __('Border / Box Shadow', 'aps-text'),
				'type' => 'select',
				'options' => array('border' => 'Border', 'box-shadow' => 'Box Shadow'),
				'desc' => __('Select border or box shadow used for image containers etc.', 'aps-text')
			),
			'icons' => array(
				'label' => __('Group Icons', 'aps-text'),
				'type' => 'select',
				'options' => array('1' => 'Display', '0' => 'Don\'t Display'),
				'desc' => __('Select to display device specifications groups icons.', 'aps-text')
			),
			'custom-css' => array(
				'label' => __('Custom CSS Stylesheet', 'aps-text'),
				'type' => 'select',
				'options' => array('1' => 'Yes, Use My Styles', '0' => 'No, Use Plugin\'s Styles'),
				'desc' => __('If you want to apply your own CSS styles (plugin\'s styles will be disabled), place your complete css code in custom-styles.css (located in "/wp-content/plugins/arena-mobiles/css/pre/" directory) and press save settings.', 'aps-text')
			)
		);
		
		return $fields;
	}
	
	// main settings fields
	function aps_main_settings_fields() {
		// get all pages
		$pages = get_pages();
		$p_options = array();
		
		foreach ($pages as $page) {
			$p_options[$page->ID] = $page->post_title;
		}
		
		$fields = array(
			'index-page' => array(
				'label' => __('Main Index Page', 'aps-text'),
				'type' => 'select',
				'options' => $p_options,
				'desc' => __('Select Main Index Page, A Page where all mobiles (posts) will display with pagination.', 'aps-text')
			),
			'index-title' => array(
				'label' => __('Main Index Heading', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter main index page heading, this will display on main index page.', 'aps-text')
			),
			'comp-page' => array(
				'label' => __('Compare Page', 'aps-text'),
				'type' => 'select',
				'options' => $p_options,
				'desc' => __('Select custom Compare Page to display custom comparisons.', 'aps-text')
			),
			'comp-list' => array(
				'label' => __('Comparisons List Page', 'aps-text'),
				'type' => 'select',
				'options' => $p_options,
				'desc' => __('Select a page to display comparisons list.', 'aps-text')
			),
			'num-products' => array(
				'label' => __('Products Per Page', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter the number of products (posts) to display in main index page, search results and brands archives.', 'aps-text')
			),
			'product-slug' => array(
				'label' => __('Product Slug', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter the product slug (slug displayed in the url of product e.g yoursite/slug/your-product).', 'aps-text')
			),
			'brands-dp' => array(
				'label' => __('Brands Dropdown Title', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter Brands dropdown title, this will display on main index page brands dropdown.', 'aps-text')
			),
			'brands-sort' => array(
				'label' => __('Brands Dropdown Sort', 'aps-text'),
				'type' => 'select',
				'options' => array('a-z' => 'Sort by Name A-Z', 'z-a' => 'Sort by Name Z-A', 'count-l' => 'Sort by Products Count L-H', 'count-h' => 'Sort by Products Count H-L', 'id' => 'Sort by Term ID'),
				'desc' => __('Select Brands dropdown sorting order, sorting order can be by name, id or products count.', 'aps-text')
			),
			'brand-slug' => array(
				'label' => __('Brand Slug', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter the brand slug (slug displayed in the url of brands archives).', 'aps-text')
			),
			'filter-title' => array(
				'label' => __('Filters Title', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter a title for filters switch (filters toggle anchor tag displaying near products sort controls)', 'aps-text')
			),
			'compare-slug' => array(
				'label' => __('Comparison Slug', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter the comparison slug (slug displayed in the url of comparison e.g yoursite/slug/product1-vs-product2).', 'aps-text')
			),
			'search-title' => array(
				'label' => __('Search Archive Heading', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter title heading for search results archive, %term% will be replaced with search term.', 'aps-text')
			),
			'brands-title' => array(
				'label' => __('Brands Archive Heading', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter title heading for Brands archive, %brand% will be replaced with brand name.', 'aps-text')
			),
			'more-title' => array(
				'label' => __('More Products Heading', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter title heading for More Products widget, %brand% will be replaced with brand name.', 'aps-text')
			),
			'more-num' => array(
				'label' => __('More Products Number', 'aps-text'),
				'type' => 'text',
				'desc' => __('How much products you want to show in More Products widget.', 'aps-text')
			),
			'rating-title' => array(
				'label' => __('Our Rating Heading', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter title heading for Our Rating widget (shown in the product overview tab).', 'aps-text')
			),
			'rating-text' => array(
				'label' => __('Our Rating Text', 'aps-text'),
				'type' => 'textarea',
				'desc' => __('Enter some information text for Our Rating widget (shown in the product overview tab).', 'aps-text')
			),
			'user-rating-title' => array(
				'label' => __('Reviews Rating Heading', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter title heading for Users Rating widget (shown in the product reviews tab).', 'aps-text')
			),
			'user-rating-text' => array(
				'label' => __('Reviews Rating Text', 'aps-text'),
				'type' => 'textarea',
				'desc' => __('Enter some information text for Users Rating widget, %num% will be replaced with number of reviews.', 'aps-text')
			),
			'post-review-note' => array(
				'label' => __('Post a Review Note', 'aps-text'),
				'type' => 'textarea',
				'desc' => __('Enter some information text for post a review (shown in the bottom of users rating widget).', 'aps-text')
			)
		);
		
		return $fields;
	}
	
	// zoom settings fields
	function aps_zoom_settings_fields() {
		$fields = array(
			'enable' => array(
				'label' => __('Switch Zoom', 'aps-text'),
				'type' => 'select',
				'options' => array('1' => __('Enable', 'aps-text'), '0' => __('Disable', 'aps-text')),
				'desc' => __('Enable or Disable main image zoom plugin.', 'aps-text')
			),
			'lensShape' => array(
				'label' => __('Lens Shape', 'aps-text'),
				'type' => 'select',
				'options' => array('square' => 'Square', 'round' => 'Round'),
				'desc' => __('Choose a lens shape for main image zoom plugin.', 'aps-text')
			),
			'lensSize' => array(
				'label' => __('Lens Size', 'aps-text'),
				'type' => 'select',
				'options' => array('100' => '100 pixels', '120' => '120 pixels', '150' => '150 pixels', '200' => '200 pixels'),
				'desc' => __('Select initial zoom lens size.', 'aps-text')
			),
			'lensBorder' => array(
				'label' => __('Lens Border', 'aps-text'),
				'type' => 'select',
				'options' => array('1' => '1 pixel', '2' => '2 pixels', '3' => '3 pixels', '5' => '5 pixels'),
				'desc' => __('Select lens border thickness in pixels.', 'aps-text')
			),
			'zoomType' => array(
				'label' => __('Zoom Type', 'aps-text'),
				'type' => 'select',
				'options' => array('lens' => 'Lens Zoom', 'window' => 'Window Zoom', 'inner' => 'Inner Zoom'),
				'desc' => __('Select the type of zoom.', 'aps-text')
			),
			'scrollZoom' => array(
				'label' => __('Scroll Zoom', 'aps-text'),
				'type' => 'select',
				'options' => array('1' => __('Enable', 'aps-text'), '0' => __('Disable', 'aps-text')),
				'desc' => __('Select zoom scrolling (mouse wheel scrolling).', 'aps-text')
			),
			'easing' => array(
				'label' => __('Easing', 'aps-text'),
				'type' => 'select',
				'options' => array('1' => __('Enable', 'aps-text'), '0' => __('Disable', 'aps-text')),
				'desc' => __('Select easing effect for zoom.', 'aps-text')
			),
			'easingAmount' => array(
				'label' => __('Easing Amount', 'aps-text'),
				'type' => 'select',
				'options' => array('2' => '2', '4' => '4', '6' => '6', '8' => '8', '10' => '10', '12' => '12'),
				'desc' => __('Select the amount of easing effect.', 'aps-text')
			),
			'responsive' => array(
				'label' => __('Responsive', 'aps-text'),
				'type' => 'select',
				'options' => array('1' => __('Enable', 'aps-text'), '0' => __('Disable', 'aps-text')),
				'desc' => __('Enable / Disable responsive layout for main image zoom plugin.', 'aps-text')
			),
			'zoomWindowWidth' => array(
				'label' => __('Zoom Window Width', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter the width of zoom window eg(400).', 'aps-text')
			),
			'zoomWindowHeight' => array(
				'label' => __('Zoom Window Height', 'aps-text'),
				'type' => 'text',
				'desc' => __('Enter the wheight of zoom window eg(400).', 'aps-text')
			)
		);
		
		return $fields;
	}
	
	// gallery lightbox settings fields
	function aps_gallery_settings_fields() {
		$fields = array(
			'enable' => array(
				'label' => __('Switch Lightbox', 'aps-text'),
				'type' => 'select',
				'options' => array('1' => __('Enable', 'aps-text'), '0' => __('Disable', 'aps-text')),
				'desc' => __('Enable or Disable Gallery Images Lightbox plugin.', 'aps-text')
			),
			'effect' => array(
				'label' => __('Lightbox Effects', 'aps-text'),
				'type' => 'select',
				'options' => array('fade' => 'Fade', 'fadeScale' => 'Fade Scale', 'slideLeft' => 'Slide Left', 'slideRight' => 'Slide Right', 'slideUp' => 'Slide Up', 'slideDown' => 'Slide Down', 'fall' => 'Fall'),
				'desc' => __('Choose the gallery images lightbox effects.', 'aps-text')
			),
			'nav' => array(
				'label' => __('Keyboard Nav', 'aps-text'),
				'type' => 'select',
				'options' => array('1' => __('Enable', 'aps-text'), '0' => __('Disable', 'aps-text')),
				'desc' => __('Enable / disable keyboard navigation for Lightbox images sliding.', 'aps-text')
			),
			'close' => array(
				'label' => __('Close on Overlay Click', 'aps-text'),
				'type' => 'select',
				'options' => array('1' => __('Enable', 'aps-text'), '0' => __('Disable', 'aps-text')),
				'desc' => __('Select to close lightbox when overlay is clicked.', 'aps-text')
			)
		);
		return $fields;
	}
	
	// APS icons list
	function get_aps_icons() {
		$icons = array (
			'android' => 'Android', 'apple' => 'Apple', 'art' => 'Art', 'battery' => 'Battery', 'bell' => 'Bell',
			'briefcase' => 'Briefcase', 'book' => 'Book', 'bullseye' => 'Bulls Eye', 'chart-pie' => 'Chart Pie',
			'cog' => 'Cog', 'comment' => 'Comment', 'cpu' => 'CPU', 'display' => 'Display', 'gauge' => 'Gauge',
			'globe' => 'Globe', 'trash' => 'Trash', 'window' => 'Window', 'folder' => 'Folder', 'money' => 'Money',
			'tablet' => 'Tablet', 'hdd' => 'HDD', 'signal' => 'Signal', 'print' => 'Print', 'attach' => 'Attach',
			'ticket' => 'Ticket', 'ram' => 'RAM', 'camera' => 'Camera', 'pictures' => 'Pictures', 'sitemap' => 'Sitemap',
			'search' => 'Search', 'radio' => 'Radio', 'media' => 'Media', 'sim' => 'SIM', 'mail' => 'Mail', 'home' => 'Home',
			'wifi' => 'WiFi', 'star' => 'Star', 'menu' => 'Menu', 'tower' => 'Tower', 'upload' => 'Upload', 'phone' => 'Phone',
			'mobile' => 'Mobile', 'info' => 'Info', 'heart' => 'Heart', 'picture' => 'Picture', 'podcast' => 'Podcast',
			'download' => 'Download', 'keyboard' => 'Keyboard', 'calendar' => 'Calendar', 'qrcode' => 'QR Code'
		);
		return $icons;
	}