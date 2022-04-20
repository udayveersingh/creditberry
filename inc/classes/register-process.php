<?php

class RegisterProcess
{
    public function __construct()
    {
        add_action('init', array($this, 'cb_session_start'));

        add_action('wp_logout', array($this, 'cb_session_end'));
        add_action('wp_login', array($this, 'cb_session_end'));

        add_action('wp_ajax_processRegistration', array($this, 'processRegistration'));
        add_action('wp_ajax_nopriv_processRegistration', array($this, 'processRegistration'));

        add_action('init', array($this, 'completeRegistrationProcess'));
    }

    /**
     * Start session
     *
     * @return void
     */
    function cb_session_start()
    {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * End session
     *
     * @return void
     */
    function cb_session_end()
    {
        session_destroy();
    }

    /**
     * Process registration
     *
     * @return void
     */
    public function processRegistration()
    {
        global $wpdb;
        $message = array();
        $message['message'] = "";
        $message['status'] = false;
        $message['alert'] = "danger";
        if (isset($_POST['processRegistrationNonce']) && wp_verify_nonce($_POST['processRegistrationNonce'], 'processRegistrationActionNonce')) {

            $post = $_POST;
            $post['phone'] = str_replace('-', '', $_POST['phone']);
            $post['ccard'] = str_replace('-', '', $_POST['ccard']);
            $email = $post['email'];

            $error = "";
            /*$tuError = CB_CONFIGS::checkAlreadyAuthenticateWithTU($post);
            if($tuError){
                $error = $tuError;
            }*/

            $isUniqueEmail = self::checkUniqueUsername($email);
            $checkEmailExistsInMCD = self::checkEmailExistsInMCD($email);
            if($isUniqueEmail || $checkEmailExistsInMCD){
                $error = "Email $email has already been taken.";
            }
           
            if ($error) {
                $message['message'] = $error;
            } else {
                $tbl_product = tblProduct;
                $tbl_productCategory = tblProductCategory;
                $resultProduct = $wpdb->get_row("SELECT $tbl_product.*,$tbl_productCategory.autoPay FROM $tbl_product JOIN $tbl_productCategory ON $tbl_product.productCategoryId = $tbl_productCategory.id WHERE $tbl_product.id=" . $post['subscription']);

                if (isset($post['funnel_id'])) {
                    /* if user registers from white label then get its plan data and store here */
                   // $resultProduct = CB_CONFIGS::getSingleFunnelPlanData($post['funnel_id'], $post['subscription']);
                }
                $currentDate = date('Y-m-d H:i:s');
                if ($resultProduct) {

                    $mcdData = [
                        'user' => [
                            'funnel_id' => isset($post['funnel_id']) ? $post['funnel_id'] : $resultProduct->mcd_funnel_id,
                            'affiliate_id' =>  $resultProduct->mcd_affiliate_id,
                            'firstname' => $post['firstname'],
                            'lastname' => $post['lastname'],
                            'email' => $post['email'],
                            'phone' => $post['phone'],
                            'street' => $post['address'],
                            'address' => $post['address'],
                            'city' => $post['city'],
                            'state' => $post['state'],
                            'zip' => $post['zipcode'],
                            'password' => $post['password'],
                            'ssn' => $post['ssn'],
                            'is_authenticated' => 0,
                            'dob' => date('Y-m-d', strtotime($post['dob'])),
                            'created_at' => $currentDate,
                            'updated_at' => $currentDate,
                            'is_whitelabel' => isset($post['funnel_id']) ? 1 : 0,
                        ],
                        'order' => null,
                        'product' => $resultProduct->alias
                    ];


                    $post['amount'] = $resultProduct->price;
                    $isRecurring = $resultProduct->autoPay;

                    try {
                        // make payment
                        $subscription = PaymentHelper::makePayment($post, null, $isRecurring);

                        if ($subscription['status'] == 'success') {

                            if (!$isRecurring) {
                                $user = new StdClass();
                                $user->id = $post['email'];
                                $customerProfile = PaymentHelper::createCustomerProfileFromTransaction($subscription['customer']['trans_id'], $user);
                                $mcdData['order']['profile_id'] =  $customerProfile['profile_id'];
                                $mcdData['order']['payment_profile_id'] = $customerProfile['payment_profile_id'];
                            } else {
                                $mcdData['order']['subscription_id'] = $subscription['customer']['customer_address_id'];
                                $mcdData['order']['payment_profile_id'] = $subscription['customer']['payment_profile_id'];
                                $mcdData['order']['profile_id'] = $subscription['customer']['profile_id'];
                            }
                            $mcdData['order']['amount_paid'] = $post['amount'];
                            $mcdData['order']['transaction_status'] = $subscription['customer']['transaction_status'];
                            $mcdData['order']['ref_id'] = $subscription['customer']['ref_id'];

                            $response = $this->saveToMcd('funnel-api/user/create', true, $mcdData);
                            if (isset($response['uuid']) && !empty($response['uuid'])) {
                                $_SESSION['uuid'] = $response['uuid'];
                            }


                            // get state id by code
                            $stateId = $this->getStateByStateCode($post['state']);

                            // insert address in 'address' table
                            $addressId = $this->insertAddress($post, $stateId->id);

                            // insert user in 'user' table
                            $userId = $this->insertUser($post, $addressId);

                            // insert login user in 'login' table
                            $loginId = $this->insertLoginUser($post, $userId);

                            // insert order in 'order table'
                            $orderId = $this->insertOrder($subscription, $userId, $resultProduct->price, $resultProduct->id);

                            $orderHistoryId = $this->insertOrderHistory($orderId);

                            $orderProductId = $this->insertOrderProduct($orderId, $resultProduct->price, $resultProduct->id);

                            $_SESSION['plan'] = $resultProduct->id;
                            $_SESSION['price'] = $resultProduct->price;
                            $_SESSION['subscription'] = $resultProduct->alias;
                            $_SESSION['funnel_id'] = isset($post['funnel_id']) ? $post['funnel_id'] : $resultProduct->mcd_funnel_id;
                            $_SESSION['ssn'] = $post['ssn'];
                            $_SESSION['acceptTerms1'] = $post['acceptTerms1'];
                            $_SESSION['acceptTerms2'] = $post['acceptTerms2'];

                            CB_CONFIGS::startUserSession($userId);

                            $message['status'] = true;
                            $message['alert'] = "success";

                            $message['message'] = "Congratulations, your account has been created. Please do not refresh this page.";
                        } else {
                            $message['message'] = $subscription['message'];
                        }
                    } catch (Exception $e) {
                        $message['message'] = $e->getMessage();
                    }
                }
            }
        } else {
            $message['message'] = "Something went wrong!";
        }



        $message['message'] = sprintf('<div class="alert alert-%s alert-dismissible" role="alert">%s</div>', $message['alert'], $message['message']);
        echo json_encode($message);
        wp_die();
    }

    /**
     * Check username is unique
     *
     * @param string $email
     * @return void
     */
    public static function checkUniqueUsername($email)
    {
        global $wpdb;
        $tblLogin = tblLogin;
        $result = $wpdb->get_row("SELECT * from $tblLogin where username='$email'");
        return $result;
    }

    /**
     * Check email is unique on MCD
     *
     * @param string $email
     * @return void
     */
    public static function checkEmailExistsInMCD($email)
    {
        $postData = http_build_query(array(
            'email' =>  $email,
        ));
        $configs = CB_CONFIGS::configs();
        $url = $configs->mcd_url . "/api/wpapi/checkEmailExistsInMCD";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result);
        if($result->StatusCode == "200"){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Save data to MCD
     *
     * @param string $url
     * @param boolean $isB3
     * @param array $data
     * @return object
     */
    public function saveToMcd($url = 'api/save_data', $isB3 = false, $data = array())
    {
        $logConfig = CB_CONFIGS::configs();
        if ($isB3 == false) {
            $_REQUEST['is_authenticated'] = 0;
            $_REQUEST['city'] = $_REQUEST['state'] = $_REQUEST['address'] = $_REQUEST['dob'] = "";
            $data = $_REQUEST;
        }

        $url = $logConfig->mcd_url . "/$url";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, 1);
        $result = curl_exec($ch);

        update_option("cb-saveToMCD-1st", $result);

        curl_close($ch);

        if ($isB3) {
            return json_decode($result, true);
        }
    }

    /**
     * Get state by state code
     *
     * @param string $code
     * @return void
     */
    public function getStateByStateCode($code)
    {
        global $wpdb;
        $tblState = tblState;
        $result = $wpdb->get_row("SELECT * from $tblState where stateCode='$code'");
        update_option('cb-getStateByStateCode', $result);
        return $result;
    }

    /**
     * Insert Address to address table
     *
     * @param object $post
     * @param integer $stateId
     * @return void
     */
    public function insertAddress($post, $stateId)
    {
        global $wpdb;
        $arr = array(
            'address1' => $post['address'],
            'city' => $post['city'],
            'stateId' => $stateId,
            'countryId' => 1,
            'zip' => $post['zipcode'],
        );
        $wpdb->insert(tblAddress, $arr);

        update_option('cb-insertAddress', $wpdb->insert_id);

        return $wpdb->insert_id;
    }

    /**
     * Insert User
     *
     * @param object $post
     * @param integer $addressId
     * @return void
     */
    public function insertUser($post, $addressId)
    {
        global $wpdb;
        $wpdb->insert(tblUser, array(
            'firstName' => $post['firstname'],
            'lastName' => $post['lastname'],
            'email' => $post['email'],
            'addressId' => $addressId,
            'userCategoryId' => CB_CONFIGS::USER_CATEGORY_CUSTOMER,
            'first_step' => date('Y-m-d H:i:s'),
            'dateCreated' => date('Y-m-d H:i:s'),
            'addedBy' => 1,
            'dob' => date('Y-m-d', strtotime($post['dob'])),
            'is_whitelabel' => isset($post['funnel_id']) ? 1 : 0
        ));

        $userId = $wpdb->insert_id;

        // $userdata = array(
        //     'ID'                    => 0,
        //     'user_login'    =>   $post['email'],
        //     'user_email'    =>   $post['email'],
        //     'user_pass'     =>   $post['password'],
        //     'first_name'    =>   $post['firstname'],
        //     'last_name'     =>   $post['lastname'],
        //     'display_name'          => $post['firstname'] . " " . $post['lastname'],
        //     'show_admin_bar_front'  => false,
        //     'role'                  => 'customer',
        // );

        // // insert this user in WORDPRESS USERS table
        // $user = wp_insert_user($userdata);
        // update_user_meta($user, 'userId', $userId);

        update_option('cb-insertUser', $userId);

        return $userId;
    }

    /**
     * Insert Login user
     *
     * @param object $post
     * @param integer $userId
     * @return void
     */
    public function insertLoginUser($post, $userId)
    {
        global $wpdb;
        $wpdb->insert(tblLogin, array(
            'username' => $post['email'],
            'isActive' => 1,
            'password' => md5($post['password']),
            'raw_pass' => $post['password'],
            'userId' => $userId,
            'accessLevelId' => CB_CONFIGS::ACCESS_LEVEL_NONE,
            'isActive' => CB_CONFIGS::INACTIVE,
        ));

        update_option('cb-insertLoginUser', $wpdb->insert_id);

        return $wpdb->insert_id;
    }

    /**
     * Insert Order
     *
     * @param array $subscription
     * @param integer $userId
     * @param float $price
     * @param integer $planId
     * @return void
     */
    public function insertOrder($subscription, $userId, $price, $planId)
    {
        global $wpdb;
        $wpdb->insert(tblOrder, array(
            'invoiceNo' => $subscription['message'],
            'userId' => $userId,
            'orderStatusId' => CB_CONFIGS::ORDER_STATUS_COMPLETE,
            'stripeToken' => time(),
            'subTotalAmount' => $price,
            'discountAmount' => 0,
            'productId' => $planId,
            'totalAmount' => $price,
            'isCompleted' => 1,
            'ipAddress' => CB_CONFIGS::getIPAddress(),
            'response' => json_encode($subscription['customer']),
            'orderResponsePayload' => json_encode($subscription['customer']),
        ));

        update_option('cb-insertOrder', $wpdb->insert_id);

        return $wpdb->insert_id;
    }

    /**
     * Insert order history
     *
     * @param integer $orderId
     * @return void
     */
    public function insertOrderHistory($orderId)
    {
        global $wpdb;
        $wpdb->insert(tblOrderHistory, array(
            'orderId' => $orderId,
            'orderStatusId' => CB_CONFIGS::ORDER_STATUS_COMPLETE,
            'notify' => 1,
        ));

        update_option('cb-insertOrderHistory', $wpdb->insert_id);

        return $wpdb->insert_id;
    }

    /**
     * Insert ordered product
     *
     * @param integer $orderId
     * @param float $price
     * @param integer $planId
     * @return void
     */
    public function insertOrderProduct($orderId, $price, $planId)
    {
        global $wpdb;
        $wpdb->insert(tblOrderProduct, array(
            'orderId' => $orderId,
            'productId' => $planId,
            'unitPrice' => $price,
            'total' => $price,
        ));

        update_option('cb-insertOrderProduct', $wpdb->insert_id);

        return $wpdb->insert_id;
    }

    /**
     * Complete registration process
     *
     * @return void
     */
    public function completeRegistrationProcess()
    {
        global $wpdb;

        $tblLogin = tblLogin;
        $tblUser = tblUser;
        $tblUserAddress = tblAddress;
        $tblState = tblState;

        if (isset($_POST['submit-complete-process'])) {

            if (isset($_POST['completeRegistrationNonce']) && wp_verify_nonce($_POST['completeRegistrationNonce'], 'completeRegistrationActionNonce')) {
                if (isset($_SESSION['userId'])) {
                    $userData = $wpdb->get_row("SELECT $tblUser.* from $tblUser where id=" . $_SESSION['userId']);

                    $wpdb->update(
                        $tblUser,
                        array(
                            'tracked' => 1,
                            'firstName' => $_POST['firstname'],
                            'lastName' => $_POST['lastname'],
                            'dob' => date('Y-m-d', strtotime($_POST['dob'])),
                        ),
                        array('id' => $_SESSION['userId'])
                    );

                    $stateData = $wpdb->get_row("SELECT * from $tblState where stateCode='" . $_POST['state'] . "'");

                    $wpdb->update(
                        $tblUserAddress,
                        array(
                            'address1' => $_POST['address'],
                            'city' => $_POST['city'],
                            'zip' => $_POST['zipcode'],
                            'stateId' => $stateData->id,
                        ),
                        array('id' => $userData->addressId)
                    );

                    $_SESSION['ssn'] = $_POST['ssn'];
                    $_SESSION['acceptTerms1'] = $_POST['acceptTerms1'];
                    $_SESSION['acceptTerms2'] = $_POST['acceptTerms2'];

                    wp_redirect(site_url() . "/questions");
                    exit();
                } else {
                    $_SESSION['cb_error'] = CB_CONFIGS::getLoginMessage();
                    wp_redirect(site_url() . "/cb-messages");
                    exit();
                }
            } else {
                $_SESSION['cb_error'] = "Something went wrong, Please check if all the information you provided is correct.";
                wp_redirect(site_url() . "/complete");
                exit();
            }
        }
    }
}
new RegisterProcess();
