(function ( $ ) {
	"use strict";

	$(function () {

		jQuery("#sunny_demo_url_purger_form").submit(function(){
			// Prevent Default Action
			event.preventDefault();

			jQuery("#sunny_demo_url_purger_result").show();
			jQuery("#sunny_demo_url_purger_form_spinner").show();
			jQuery("#sunny_demo_url_purger_button").attr("disabled", "disabled")

			var data = {
				action:     'sunny_demo_purge_url',
				nonce:      jQuery('#sunny_demo_url_purger_nonce').val(),// The security nonce
				"post_url": jQuery('#sunny_demo_purge_url').val()
		};

		
		jQuery.post( ajaxurl, data, function (response) {

			// React to the response
			var output = "<p>" + response.message + "</p>";
			jQuery("#sunny_demo_url_purger_result").append(jQuery(output).fadeIn('slow'));
			jQuery("#sunny_demo_url_purger_form_spinner").hide();
			jQuery("#sunny_demo_url_purger_button").removeAttr("disabled");
		}, 'json');

		// Prevent Default Action Again
		return false;
	}); // end #url_purger_form submit

	});

}(jQuery));