<?php

get_header();

$months = CB_CONFIGS::getMonthList();
$years = CB_CONFIGS::getYearList();
$states = CB_CONFIGS::getStateList();
$subscriptionPlans = CB_CONFIGS::getSubscriptionPlan();


$selectedType = 0;
if (isset($_GET['subscriptionType']) && !empty($_GET['subscriptionType'])) {
    $selectedType = $_GET['subscriptionType'];
}

?>
<div id="content-vw">
    <div class="container">
        <div class="middle-align">
            <div class="loader-parent">
                <div class="loader"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <section id="cb-page">
                        <div class="row">
                            <div class="col-md-12" id="alert-message"></div>
                        </div>
                        <form action="" method="post" name="cbForm" id="cbForm">
                            <input type="hidden" name="action" value="processRegistration">
                            <input type="hidden" name="funnel_id" value="<?php echo $userData->id ?>">
                            <?php wp_nonce_field('processRegistrationActionNonce', 'processRegistrationNonce'); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 style="text-align: left" class="vc_custom_heading section-title left">Payment details</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="firstname" class="form-control" id="firstname" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="lastname" class="form-control" id="lastname" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" id="email" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <input type="text" name="dob" id="dob" class="form-control datepicker" placeholder="mm/dd/yyyy" required readonly />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone number</label>
                                        <input type="text" minlength="12" maxlength="12" name="phone" id="phone" class="form-control" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>SSN</label>
                                        <input type="text" name="ssn" id="ssn" maxlength="9" minlength="9" class="form-control" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" id="password" minlength="8" maxlength="25" class="form-control" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Credit card</label>
                                        <input type="text" name="ccard" id="ccard" class="form-control" maxlength="19" minlength="18" required />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <img src="<?php echo  get_template_directory_uri() . "/images/authorize.png"; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Expiration Month</label>
                                        <select name="cardMonth" id="cardMonth" class="form-control" required>
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($months as $mk => $mv) {
                                                echo sprintf('<option value="%s">%s</option>', $mk, $mv);
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Expiration Year</label>
                                        <select name="cardYear" id="cardYear" class="form-control" required>
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($years as $yk => $yv) {
                                                echo sprintf('<option value="%s">%s</option>', $yk, $yv);
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>CVV</label>
                                        <input type="password" minlength="3" maxlength="4" name="cvv" id="cvv" class="form-control" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Reports and Plans</label>
                                        <select name="subscription" id="subscription" class="form-control" required>
                                            <option value="">Select</option>
                                            <?php
                                            if ($pricing) {
                                                foreach ($pricing as $sk => $sv) {
                                                    $selected = $selectedType == $sk ? "selected" : "";
                                                    echo sprintf('<option value="%s" %s>%s</option>', $sk, $selected, $sv->name);
                                                }
                                            } else {
                                                foreach ($subscriptionPlans as $sk => $sv) {
                                                    $selected = $selectedType == $sv->id ? "selected" : "";
                                                    echo sprintf('<option value="%s" %s>%s</option>', $sv->id, $selected, $sv->name);
                                                }
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="address" id="address" class="form-control" required />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Postal code</label>
                                        <input type="text" name="zipcode" minlength="5" maxlength="5" id="zipcode" class="form-control" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" name="city" id="city" class="form-control" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select name="state" id="state" class="form-control" required>
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($states as $stk => $stv) {
                                                echo sprintf('<option value="%s">%s</option>', $stv->stateCode, $stv->name);
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h5 style="text-align: left" class="vc_custom_heading section-title left">Terms and Conditions</h5>
                                </div>
                                <div class="col-md-12">
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

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="submit" class="btn btn-secondary btn-lg btn-3b-theme" id="btn-3b-continue" value="Continue">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-md-12 register-bottom">
                                <div><img src="<?php echo get_template_directory_uri() ?>/images/img2.png" class="image-responsive center-block"></div>
                                <div><span class="body-txt">Secure Checkout</span></div>
                                <p class="snd_txt_1">Your credit card information will be safely encrypted and transmitted using SSL technology to eleminate secuirty risks.</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>