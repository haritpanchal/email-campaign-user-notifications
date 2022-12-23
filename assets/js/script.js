(function ($) {
  jQuery(document).ready(function () {
    $("#email_crons_users").select2();
    $("#email_crons_roles").select2();

    // if ($("#email_crons_roles").select2("val") !== []) {
    //   var roles = $("#email_crons_roles").select2("val");
    //   jQuery.ajax({
    //     url: localize_variable.ajax_url,
    //     type: "POST",
    //     data: {
    //       action: "email_crons_change_users",
    //       roles: roles,
    //     },
    //     success: function (response) {
    //       if (response !== null) {
    //         console.log(response);
    //         $("#email_crons_users").append(response);
    //       }
    //     },
    //   });
    // }

    $("#email_crons_sent_test_email").on("click", function () {
      var test_email = $("#email_crons_test_email").val();
      jQuery.ajax({
        url: localize_variable.ajax_url,
        type: "POST",
        data: {
          action: "send_test_email_action",
          email: test_email,
        },
        beforeSend:function(){
          jQuery(".test_message").hide();
        },
        success: function (response) {
          jQuery(".test_message").show();
          // console.log(response);
          jQuery(".test_message p strong").text(response.data.message);
          if (response.success === true) {
            jQuery(".test_message").addClass("notice-success");
          } else {
            jQuery(".test_message").addClass("notice-error");
          }

          setTimeout(function () {
            jQuery(".test_message").hide();
          }, 5000);
        },
      });
    });

    $("#start_sending_email_button").on("click", function () {
      jQuery.ajax({
        url: localize_variable.ajax_url,
        type: "POST",
        data: {
          action: "schedule_cron",
        },
        beforeSend:function(){
          jQuery(".cron_settings_message").hide();
        },
        success: function (response) {
          jQuery(".cron_settings_message").show();

          jQuery(".cron_settings_message p").text(response.data.message);
          if (response.success === true) {
            jQuery(".cron_settings_message").addClass("notice-success");
          } else {
            jQuery(".cron_settings_message").addClass("notice-error");
          }

          setTimeout(function () {
            jQuery(".cron_settings_message").hide();
          }, 5000);
        },
      });
    });
  });
})(jQuery);
