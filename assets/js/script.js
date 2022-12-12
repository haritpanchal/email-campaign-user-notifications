(function ($) {
jQuery( document ).ready(function() {
	$('#email_crons_sent_test_email').click(function(){
		var test_email = $('#email_crons_test_email').val();
		jQuery.ajax({
			url: localize_variable.ajax_url,
			type: "POST",
			data: {
			  action: "send_test_email_action",
			  email: test_email,
			},
			success: function (response) {
				jQuery('.test_message').show();
				// console.log(response);
				jQuery('.test_message p').text(response.data.message);
				if(response.success === true){
					jQuery('.test_message').addClass("notice-success");
				}
				else{
					jQuery('.test_message').addClass("notice-error");
				}
			},
		  });
	})
});

})(jQuery);
