(function ($) {

    var register = {

        init: function () {
            register.initializeFields();

            register.enabledQuestionSubmitButton();
            $("#btn-3b-continue").on("click", this.submit3bForm);
            $("#btn-cb-login").on("click", this.submitLoginForm);
            $("#submit-complete-process").on("click", this.submitCompleteProcess);
            $(".iRadio input").on("change", this.enabledQuestionSubmitButton);
            $("#authenticate-questions").on("click", this.submitQuestions);

            register.check3bPage();
        },
        initializeFields: function () {
            if ($(".datepicker").length > 0) {
                $('.datepicker').datepicker({ 
                    format: 'mm/dd/yyyy', 
                    autoclose: true, 
                    // endDate: "-1d" ,
                    endDate: "-18Y" ,
                    yearRange: '-110:-18'
                });
            }
            if ($("#ccard").length > 0) {
                $('#ccard').mask('0000-0000-0000-0000');
            }
            if ($('#phone').length > 0) {
                $('#phone').mask('000-000-0000');
            }
            if ($('#ssn').length > 0) {
                $('#ssn').mask('000000000');
            }
            if ($('#dob').length > 0) {
                $('#dob').mask('00/00/0000', { placeholder: "mm/dd/yyyy" });
            }

            setTimeout(function () {
                $('.alert.alert-dismissible').fadeOut('fast');
            }, 10000);

        },
        submit3bForm: function (e) {
            $("#cbForm").validate({
                'rules': {
                    firstname: {
                        required: true,
                    },
                    lastname: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                        pattern: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/
                    },
                    dob: {
                        required: true,
                    },
                    phone: {
                        required: true,
                        minlength: 12,
                        maxlength: 12
                    },
                    ssn: {
                        required: true,
                        minlength: 9,
                        maxlength: 9
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 25
                    },
                    ccard: {
                        required: true,
                        minlength: 18,
                        maxlength: 19
                    },
                    cardMonth: {
                        required: true,
                    },
                    cardYear: {
                        required: true,
                    },
                    cvv: {
                        required: true,
                        minlength: 3,
                        maxlength: 4
                    },
                    subscription: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    zipcode: {
                        required: true,
                        minlength: 5,
                        maxlength: 5
                    },
                    city: {
                        required: true,
                    },
                    state: {
                        required: true,
                    },
                    acceptTerms1: {
                        required: true,
                    },
                    acceptTerms2: {
                        required: true,
                    },
                },
                'messages': {
                    firstname: "The firstname field is required.",
                    lastname: "The lastname field is required.",
                    email: {
                        required: "The email field is required.",
                        email: "This field must be a valid email address.",
                        pattern: "This field must be a valid email address.",
                    },
                    dob: "The date of birth field is required.",
                    phone: {
                        required: "The phone field is required.",
                        minlength: "Minimum length is 12.",
                        maxlength: "Maximum length is 12."
                    },
                    ssn: {
                        required: "The SSN field is required.",
                        minlength: "Minimum length is 9.",
                        maxlength: "Maximum length is 9.",
                    },
                    password: {
                        required: "The password field is required.",
                        minlength: "Minimum length is 8.",
                        maxlength: "Maximum length is 25."
                    },
                    ccard: {
                        required: "The credit card field is required.",
                        minlength: "Minimum length is 18.",
                        maxlength: "Maximum length is 19."
                    },
                    cardMonth: "The credit card expiry month field is required.",
                    cardYear: "The credit card expiry year field is required.",
                    cvv: {
                        required: "The credit card cvv field is required.",
                        minlength: "Minimum length is 3.",
                        maxlength: "Maximum length is 4."
                    },
                    subscription: "The subscription plan is required.",
                    address: "The address field is required.",
                    zipcode: {
                        required: "The postal code field is required.",
                        minlength: "Minimum length is 5.",
                        maxlength: "Maximum length is 5."
                    },
                    city: "The city field is required.",
                    state: "The state field is required.",
                    acceptTerms1: "Please agree to the terms.",
                    acceptTerms2: "Please agree to the terms.",
                },
                submitHandler: function (form) {
                    $(".loader-parent").show();
                    // setTimeout(function () {

                    //     $("#cb-page").hide();

                    //     $("#alert-message").append('<div class="alert alert-success alert-dismissible" role="alert">Congratulations, your account has been created.Please do not refresh this page.</div>');

                    //     $(".loader-parent").hide();

                    // }, 3000);

                    // setTimeout(function () {
                    //     // $("#cb-questions").show();

                    //     // $('html, body').animate({
                    //     //     scrollTop: $('#cb-questions').offset().top - 50
                    //     // }, 'slow');

                    //     window.location = jQuery(location).attr('href') + "questions";

                    // }, 2000)

                    $.ajax({
                        type: "POST",
                        url: registerObj.ajax_url,
                        data: $(form).serialize(),
                        success: function (response) {
                            response = JSON.parse(response);
                            $("#alert-message").empty().append(response.message);
                            $(".loader-parent").hide();

                            $('html, body').animate({
                                scrollTop: $('#cb-page').offset().top - 50
                            }, 'slow');
                            if (response.status) {
                                $("#cbForm").remove();
                                setTimeout(function () {
                                    window.location = registerObj.site_url + "/questions";
                                }, 2000);
                            }
                        }
                    });
                    return false;
                }
            });
        },

        enabledQuestionSubmitButton: function (e) {
            if ($(".iRadio input:checked").length == $(".q_series").length) {
                $(".submit-questions input").removeAttr("disabled");
            } else {
                $(".submit-questions input").attr("disabled", true);
            }
        },
        submitQuestions: function (e) {
            $(".loader-parent").show();
            // setTimeout(function () {
            //     $(".loader-parent").hide();
            //     window.location = "http://newdev.mycreditdash.com/get_started/mcd/7d2e9840-8f6e-11e7-91da-c74eba7ecf3d";
            // }, 3000);
        },
        submitLoginForm: function (e) {
            $("#frm-login").validate({
                'rules': {
                    username: {
                        required: true,
                    },
                    password: {
                        required: true
                    },
                },
                'messages': {
                    username: {
                        required: "The username is required.",
                    },
                    password: {
                        required: "The password is required.",
                    },
                }
            });
        },
        submitCompleteProcess: function (e) {
            $("#frm-complete-process").validate({
                'rules': {
                    firstname: {
                        required: true,
                    },
                    lastname: {
                        required: true,
                    },
                    dob: {
                        required: true,
                    },
                    ssn: {
                        required: true,
                        minlength: 9,
                        maxlength: 9
                    },
                    address: {
                        required: true,
                    },
                    zipcode: {
                        required: true,
                        minlength: 5,
                        maxlength: 5
                    },
                    city: {
                        required: true,
                    },
                    state: {
                        required: true,
                    },
                    acceptTerms1: {
                        required: true,
                    },
                    acceptTerms2: {
                        required: true,
                    },
                },
                'messages': {
                    firstname: "The firstname field is required.",
                    lastname: "The lastname field is required.",
                    dob: "The date of birth field is required.",
                    ssn: {
                        required: "The SSN field is required.",
                        minlength: "Minimum length is 9.",
                        maxlength: "Maximum length is 9.",
                    },
                    address: "The address field is required.",
                    zipcode: {
                        required: "The postal code field is required.",
                        minlength: "Minimum length is 5.",
                        maxlength: "Maximum length is 5."
                    },
                    city: "The city field is required.",
                    state: "The state field is required.",
                    acceptTerms1: "Please agree to the terms.",
                    acceptTerms2: "Please agree to the terms.",
                }
            });
        },
        check3bPage: function () {
            $(".page-template-3b .menu-item a").attr("target", "_blank");
        }
    }

    register.init();

})(jQuery);