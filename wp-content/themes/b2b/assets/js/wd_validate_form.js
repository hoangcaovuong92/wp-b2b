//****************************************************************//
/*                          VALIDATE FORM JS                      */
//****************************************************************//
jQuery(document).ready(function($){
    "use strict";
	wd_validate_comment_form(); //Validate Comment form
    wd_validate_edit_account_form(); //Validate edit account page (woo)
});

//****************************************************************//
/*                          FUNCTIONS                             */
//****************************************************************//
//Validate Comment form
if (typeof wd_validate_comment_form != 'function') { 
    function wd_validate_comment_form() {
        jQuery('.comment-reply-link').on('click', function(){
            setTimeout(function(){
                jQuery('body,html').animate({
                    scrollTop: jQuery('#respond').offset().top
                }, 1000);
                jQuery('#respond').find('#comment').focus();
            }, 300);
        });

        jQuery("#wd-comment-respond-form").validate({
            rules: {
                author: "required",
                email: {
                    required: true,
                    email: true,
                    minlength: 1,
                },
                comment: "required",
            },
            messages: {
                author: "Please enter your name!",
                email: {
                    required: "Please enter your email!",
                    minlength: "Your password must be at least 5 characters long",
                },
                comment: "Please enter your comment!",
            }
        });
    }
}

//Validate edit account page (woo)
if (typeof wd_validate_edit_account_form != 'function') { 
    function wd_validate_edit_account_form() {
        jQuery(".edit-account").validate({
            rules: {
                account_first_name: "required",
                account_last_name: "required",
                account_email: {
                    required: true,
                    email: true,
                    minlength: 1,
                },
                password_again: {
                  equalTo: "#password_1"
                }
            },
            messages: {
                account_first_name: "Please enter your first name!",
                account_last_name: "Please enter your last name!",
                account_email: {
                    required: "Please enter your email!",
                    minlength: "Your password must be at least 5 characters long",
                },
                password_again: {
                  equalTo: "Retype the password incorrectly"
                }
            }
        });
    }
}