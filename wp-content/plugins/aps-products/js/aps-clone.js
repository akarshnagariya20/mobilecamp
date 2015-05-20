// post script
jQuery(document).ready(function($) {
	$("a.clone-this").click(function(e) {
		
		// submit post data to clone
		$.ajax({
			url: ajaxurl,
			type: "POST",
			data: {
				id: $(this).attr("data-id"),
				code: $(this).attr("data-code"),
				action: "clone_product"
			},
			success: function(res) {
				if (res == true) {
					// Reload the page
					location.reload();
				}
			}
		});
		e.preventDefault();
	});
});