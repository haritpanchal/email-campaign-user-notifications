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

    $("#email_crons_sent_test_email").click(function () {
      var test_email = $("#email_crons_test_email").val();
      jQuery.ajax({
        url: localize_variable.ajax_url,
        type: "POST",
        data: {
          action: "send_test_email_action",
          email: test_email,
        },
        success: function (response) {
          jQuery(".test_message").show();
          // console.log(response);
          jQuery(".test_message p").text(response.data.message);
          if (response.success === true) {
            jQuery(".test_message").addClass("notice-success");
          } else {
            jQuery(".test_message").addClass("notice-error");
          }
        },
      });
    });

    $("#email_crons_roles").on("change", function () {
      $("#email_crons_users").val(null).trigger("change");
      $("#email_crons_select_all").prop("checked", false);
      var roles = $("#email_crons_roles").select2("val");

      jQuery.ajax({
        url: localize_variable.ajax_url,
        type: "POST",
        data: {
          action: "email_crons_change_users",
          roles: roles,
        },
        success: function (response) {
          $("#email_crons_users").empty().append(response);
        },
      });
    });

    $("#email_crons_select_all").click(function () {
      if ($("#email_crons_select_all").is(":checked")) {
        $("#email_crons_users > option").prop("selected", "selected");
        $("#email_crons_users").trigger("change");
      } else {
        $("#email_crons_users > option").removeAttr("selected");
        $("#email_crons_users").trigger("change");
      }
    });
  });
})(jQuery);
