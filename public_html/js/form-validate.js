$(document).ready(function(){

  /**
  * jQuery Validator Function
  *
  * This function provides a jQuery front-end validation for the contact form.
  * If all tests set up here pass, the form data is AJAX submitted
  * to the mailer.php file.
  *
  * Update this file as needed for your form.
  * All ids and name values must match up to your form here.
  *
  * @author Will Tredway <jwilliamtredway@gmail.com>
  **/

  /* begin validate function here */
  $("#superbit-contact-form").validate({

    // setup handling of form errors
    debug: true,
    errorClass: "alert alert-danger",
    errorLabelContainer: "#output-area",
    errorElement: "div",

    // rules here define what is good or bad input
    // each rule starts with the form input element's NAME attribute
    rules: {
      contactName: {
        required: true
      },
      contactEmail: {
        email: true,
        required: true
      },
      contactMessage: {
        required: true,
        maxlength: 300,
        minlength: 10
      }
    },

    // error messages to display to the end user when rules above don't pass
    messages: {
      contactName: {
        required: "Please enter your name."
      },
      contactEmail: {
        email: "Please enter a valid email address.",
        required: "Please enter a valid email address."
      },
      contactMessage: {
        maxlength: "Max length of message 300 characters"
      }
    },

    // AJAX submit the form data to back end if rules pass
    submitHandler: function(form) {
      $("#superbit-contact-form").ajaxSubmit({
        type: "POST",
        url: $("#superbit-contact-form").attr("action"),

        success: function(ajaxOutput) {
          // clear the output area's formatting
          $("#output-area").css("display", "");

          // write the server's reply to the output area
          $("#output-area").html(ajaxOutput);

          // reset the form if it was successful
          if($(".alert-success").length >= 1) {
            $("#superbit-contact-form")[0].reset();
          }
        }
      })
    }

  });/* end validate function here */

});/*end document.ready()*/