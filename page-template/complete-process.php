<?php

/**
 * Template Name: Complete-process
 */

get_header();
$states = CB_CONFIGS::getStateList();
$subscriptionPlans = CB_CONFIGS::getSubscriptionPlan();
$dob = isset($_SESSION['dob']) ? date('m-d-Y', strtotime($_SESSION['dob'])) : "";

?>
<div id="content-vw">
    <div class="container">
        <div class="middle-align">
            <div class="loader-parent">
                <div class="loader"></div>
            </div>
            <section id="complete-process">
                <div class="row">
                    <?php
                    require get_template_directory() . '/page-template/message-error.php';
                    require get_template_directory() . '/page-template/message-info.php';

                    if (isset($_SESSION['status_code']) &&  $_SESSION['status_code'] == 122) {
                        ?>
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h3 class="text-center h3">We'are sorry!</h3>
                                <h4>We are unable to locate the information needed to verify your identity with TransUnion&REG;.</h4>
                                <p>Sometimes the last four of your Social Security number are not enough to accurately locate your credit file. To continue trying.</p>
                                <ul>
                                    <li>Please check all your personal information and if you've lived at the current address for less than six(6) months please select No.</li>
                                    <li>Enter your full Social Security number (SSN) and click on the "Verify and Continue" button.</li>
                                </ul>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>

                <form action="" id="frm-complete-process" name="frm-complete-process" method="post">
                    <input type="hidden" name="action" value="completeRegistrationProcess">
                    <?php wp_nonce_field('completeRegistrationActionNonce', 'completeRegistrationNonce'); ?>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle fa-fw fa-lg"></i>
                                <strong>Note:</strong> TO GRANT YOUR ACCESS TO VIEW SECURE INFORMATION, PLEASE PROVIDE THE FOLLOWING PERSONALLY IDENTIFIABLE INFORMATION. IN ORDER TO PULL DATA FROM TRANSUNION, RAPID CREDIT REPORTS, INC REQUIRES THIS INFORMATION.
                            </div>
                        </div>


                        <div class="col-md-12">
                            <h5 style="text-align: left" class="vc_custom_heading section-title left">Complete your registration</h5>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">

                                <label>Full Social Security Number</label>
                                <input type="password" name="ssn" maxlength="9" minlength="9" class="form-control" id="ssn" placeholder="_________" required />

                            </div>
                        </div>

                        <div class="col-md-12">
                            <h5 style="text-align: left" class="vc_custom_heading section-title left">Review Your Personal Information</h5>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="firstname" value="<?php echo isset($_SESSION['firstName']) ? $_SESSION['firstName'] : "" ?>" class="form-control" id="firstname" required />
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="lastname" value="<?php echo isset($_SESSION['lastName']) ? $_SESSION['lastName'] : "" ?>" class="form-control" id="lastname" required />
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="text" name="dob" id="dob" value="<?php echo $dob; ?>" class="form-control datepicker" placeholder="mm/dd/yyyy" required />
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" id="address" value="<?php echo isset($_SESSION['addressLine1']) ? $_SESSION['addressLine1'] : "" ?>" class="form-control" required />
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" id="city" value="<?php echo isset($_SESSION['city']) ? $_SESSION['city'] : "" ?>" class="form-control" required />
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Postal code</label>
                                <input type="text" name="zipcode" value="<?php echo isset($_SESSION['zip']) ? $_SESSION['zip'] : "" ?>" minlength="5" maxlength="5" id="zipcode" class="form-control" required />
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>State</label>
                                <select name="state" id="state" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php
                                    foreach ($states as $stk => $stv) {
                                        $selected = isset($_SESSION['stateId']) && $_SESSION['stateId'] == $stv->id ? "selected" : "";
                                        echo sprintf('<option value="%s" %s>%s</option>', $stv->stateCode, $selected, $stv->name);
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h5 style="text-align: left" class="vc_custom_heading section-title left">Terms and Conditions</h5>
                        </div>

                        <div class="col-md-8">
                            <div class="agree">
                                <input type="checkbox" name="acceptTerms1" id="acceptTerms1" value="1" required />
                                <label class="lbl-chk">I have read the terms and agree.</label>
                                <div class="alert alert-default terms-box">
                                    <?php get_template_part('template-parts/terms1', 'page'); ?>
                                </div>
                            </div>
                            <div class="agree">
                                <input type="checkbox" name="acceptTerms2" id="acceptTerms2" value="1" required />
                                <label class="lbl-chk">I have read the terms and agree.</label>
                                <div class="alert alert-default terms-box">
                                    <?php get_template_part('template-parts/terms2', 'page'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <input type="submit" value="Complete registration" name="submit-complete-process" id="submit-complete-process" class="btn btn-secondary btn-lg btn-3b-theme" />
                        </div>
                    </div>
                </form>
            </section>

        </div>
    </div>
</div>

<?php get_footer(); ?>