(function ($) {
  jQuery(document).ready(function () {
    $("#email_crons_roles").select2();

    $("#email_crons_sent_test_email").on("click", function (e) {
      var test_email = jQuery("#email_crons_test_email").val();
      jQuery.ajax({
        url: localize_variable.ajax_url,
        type: "POST",
        data: {
          action: "send_test_email_action",
          email: test_email,
        },
        beforeSend: function () {
          jQuery(".test_message").hide();
          jQuery("#email_crons_sent_test_email").val("Sending Test Email");
          jQuery("#email_crons_sent_test_email").attr("disabled", true);
        },
        success: function (response) {
          jQuery("#email_crons_sent_test_email").attr("disabled", false);
          jQuery("#email_crons_sent_test_email").val("Send Test Email");
          jQuery(".test_message").show();
          // console.log(response);
          jQuery(".test_message p strong").text(response.data.message);
          if (response.success === true) {
            jQuery(".test_message").addClass("notice-success");
            jQuery("#email_crons_test_email").val("");
          } else {
            jQuery(".test_message").addClass("notice-error");
          }

          setTimeout(function () {
            jQuery(".test_message").hide();
            jQuery(".test_message").removeClass("notice-success");
            jQuery(".test_message").removeClass("notice-error");
          }, 5000);
        },
      });
    });

    $("#email_crons_sent_test_email_form").on("keypress", function (e) {
      if (e.keyCode === 13 || e.keyCode === 32) {
        return false;
      }
    });

    $("#start_sending_email_button").on("click", function () {
      jQuery.ajax({
        url: localize_variable.ajax_url,
        type: "POST",
        data: {
          action: "schedule_cron",
        },
        beforeSend: function () {
          jQuery(".cron_settings_message").hide();
          jQuery("#start_sending_email_button").attr("disabled", true);
        },
        success: function (response) {
          jQuery(".cron_settings_message").show();
          jQuery(".cron_settings_message p").text(response.data.message);
          if (response.success === true) {
            jQuery(".cron_settings_message").addClass("notice-success");
            jQuery("#start_sending_email_button").after(
              '<p class="description in_progress"><strong>In Progress. Can not start another campaign until this one is finished.<strong/></p>'
            );
          } else {
            jQuery(".cron_settings_message").addClass("notice-error");
          }

          setTimeout(function () {
            jQuery(".cron_settings_message").hide();
            jQuery(".cron_settings_message").removeClass("notice-error");
            jQuery(".cron_settings_message").removeClass("notice-success");
          }, 5000);
        },
      });
    });
  });
})(jQuery);
