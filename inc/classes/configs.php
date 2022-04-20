<?php

use App\Models\Config;

class CB_CONFIGS
{

    const COMPANY_NAME = "creditberry.com";
    const COMPANY_ADDRESS = "2555 East Colorado Blvd., Suite 201, Pasadena, CA, 91107";
    const COMPANY_PHONE = "1-800-727-4322";
    const COMPANY_EMAIL = "info@creditberry.com";
    // user consts
    const USER_CATEGORY_ENDUSER = 1;
    const USER_CATEGORY_CUSTOMER = 2;
    const USER_CATEGORY_RCR = 3;
    const USER_CATEGORY_RENTING = 4;
    const USER_CATEGORY_AUTO = 5;
    const USER_CATEGORY_MEDICAL = 6;
    const USER_CATEGORY_PROFESSION = 7;
    const USER_CATEGORY_CONTRACTOR = 8;
    const USER_CATEGORY_LEGAL = 9;
    const USER_CATEGORY_EVALUATION = 10;
    const USER_CATEGORY_FINANCIAL = 11;

    // access consts
    const ACCESS_LEVEL_NONE = 1;
    const ACCESS_LEVEL_BASIC = 2;
    const ACCESS_LEVEL_PARTNER = 3;
    const ACCESS_LEVEL_STAFF = 4;
    const ACCESS_LEVEL_ADMIN = 5;
    const ACCESS_LEVEL_SUPER_ADMIN = 5;


    // settings consts
    const ACTIVE = 1;
    const INACTIVE = 0;

    const NOTIFY_FROM_EMAIL = 'ERROR ALERT<error-alert@ewebconsult.com>';
    const CACHE_SECRET_KEY = 'bd90cea3-e0f4-4dae-8bd7-5c6f05469efb';
    const HTTP_STATUS_FAILED = 'FAILURE';
    const RCR_API_KEY = '83d7efb8-323a-47f8-960f-42dae9249a04';

    const ERROR_ACCOUNT_DISABLED     = 101;
    const ERROR_ACCOUNT_LOGIN_ATTEMPTS = 109;

    // Order consts
    const ORDER_STATUS_PENDING = 1;
    const ORDER_STATUS_PROCESSING = 2;
    const ORDER_STATUS_COMPLETE = 3;

    const USER_QUESTION_NEXT_RETRY_DAYS = 30;
    const USER_QUESTION_NUMBER_OF_TRIES = 3;

    const ERROR_NONE = 0;
    const ERROR_USERNAME_INVALID = 1;
    const ERROR_PASSWORD_INVALID = 2;
    const ERROR_UNKNOWN_IDENTITY = 100;

    public function __construct()
    {
        // add_filter('wp_nav_menu_objects', array($this, 'login_logout_link'), 10, 2);
        add_action('template_redirect', array($this, 'cb_check_url_redictions'));
        // add_action('admin_menu', array($this, 'cb_admin_menus'));
        // add_action('admin_init', array($this,'save_cb_pages'));
        // add_action('init', array($this, 'dbScript'));
        // add_action('init', array($this, 'testfunction'));
    }

    function testfunction()
    { }



    // function dbScript()
    // {
    //     global $wpdb;
    //     $tblLogin = tblLogin;
    //     $tblUser = tblUser;

    //     $dbScriptUpdated = get_option('dbScriptUpdated');
    //     if (!$dbScriptUpdated) {

    //         $result = add_role(
    //             strtolower("customer"),
    //             __("Customer"),
    //             array(
    //                 'read' => true,
    //             )
    //         );

    //         $getUsers = $wpdb->get_results("SELECT $tblUser.*, $tblLogin.* FROM $tblUser INNER JOIN $tblLogin ON $tblUser.id = $tblLogin.userId");

    //         $c = 0;
    //         foreach ($getUsers as $key => $value) {
    //             $role = $value->accessLevelId == 5 ? 'administrator' : 'customer';

    //             $userdata = array(
    //                 'user_login' => $value->email,
    //                 'user_email' => $value->email,
    //                 'user_pass' => $value->password,
    //                 'first_name' => $value->firstName,
    //                 'last_name' => $value->lastName,
    //                 'display_name' => $value->firstName . " " . $value->lastName,
    //                 'role' => $role,
    //             );

    //             $user = wp_insert_user($userdata);

    //             $usermeta = array(
    //                 'userId' => $value->userId,
    //                 // 'addedBy' => $value->addedBy,
    //                 // 'companyName' => $value->companyName,
    //                 // 'addressId' => $value->addressId,
    //                 // 'avatarId' => $value->avatarId,
    //                 // 'userCategoryId' => $value->userCategoryId,
    //                 // 'dob' => $value->dob,
    //                 // 'clientKey' => $value->clientKey,
    //                 // 'customerKey' => $value->customerKey,
    //                 // 'enrolled' => $value->enrolled,
    //                 // 'simulator_enrolled' => $value->simulator_enrolled,
    //                 // 'enrolledOn' => $value->enrolledOn,
    //                 // 'cancelledOn' => $value->cancelledOn,
    //                 // 'dateCreated' => $value->dateCreated,
    //                 // 'first_step' => $value->first_step,
    //                 // 'second_step' => $value->second_step,
    //                 // 'third_step' => $value->third_step,
    //                 // 'authstatus' => $value->authstatus,
    //                 // 'auth_by' => $value->auth_by,
    //                 // 'tracked' => $value->tracked,
    //                 // 'reference' => $value->reference,
    //                 // 'raw_pass' => $value->raw_pass,
    //                 // 'isTempPwd' => $value->isTempPwd,
    //                 // 'lastLogin' => $value->lastLogin,
    //                 // 'accessLevelId' => $value->accessLevelId,
    //                 // 'pwdExpireDate' => $value->pwdExpireDate,
    //                 // 'isActive' => $value->isActive,
    //                 // 'deactivateon' => $value->deactivateon,
    //                 // 'numLogin' => $value->numLogin,
    //             );

    //             foreach ($usermeta as $uk => $uv) {
    //                 update_user_meta($user, $uk, $uv);
    //             }
    //             $c++;

    //             if ($c == count($getUsers)) {
    //                 update_option('dbScriptUpdated', true);
    //             }
    //         }
    //     }
    // }


    // function register_cb_menu()
    // {
    //     add_menu_page('Credit Berry', 'Credit Berry', 'manage_options', 'creditberry-settings', array($this,'creditberrySettings'));
    // }

    // function creditberrySettings(){

    //     require get_template_directory() . '/page-template/admin/admin.php';
    // }

    // function save_cb_pages(){
    //     if(isset($_POST['save_cb_pages'])){

    //         echo '<pre>';
    //         print_r($_POST);
    //         echo '</pre>';
    //         die;
    //     }
    // }

    public static $userCategoryNames = array(
        self::USER_CATEGORY_ENDUSER => 'End User',
        self::USER_CATEGORY_CUSTOMER => 'Customer',
        self::USER_CATEGORY_RCR => 'Rapid Credit Reports Inc',
        self::USER_CATEGORY_RENTING => 'Renting/Leasing Property',
        self::USER_CATEGORY_AUTO => 'Auto Financing',
        self::USER_CATEGORY_MEDICAL => 'Medical Financing',
        self::USER_CATEGORY_PROFESSION => 'Profession Financing Services',
        self::USER_CATEGORY_CONTRACTOR => 'Contractor Evaluation',
        self::USER_CATEGORY_LEGAL => 'Legal Advice',
        self::USER_CATEGORY_EVALUATION => 'Evaluation Financial Status',
        self::USER_CATEGORY_FINANCIAL => 'Financial Planning and Tax Advice',
    );

    public function cb_check_url_redictions()
    {
        if (is_page('3b')) {
            if (isset($_SESSION['userId'])) {
                CB_CONFIGS::redirectToUserSpecificPage();
            }
        }

        if (is_page('cb-messages')) {
            if ($_SESSION['cb_error'] == ""  || $_SESSION['cb_info'] == "") {
                wp_redirect(site_url());
                exit();
            }
        }

        if (is_page('complete')) {

            CB_CONFIGS::is_user_loggedin();

            $userToken = CB_CONFIGS::checkUserAutenticatedWithTU();

            $configs = CB_CONFIGS::configs();
            if ($userToken) {
                if (isset($_SESSION['userHomeUrl']) && $_SESSION['userHomeUrl'] != "") {
                    $_SESSION['cb_info'] = sprintf("You are already authenticate.Please <a href='%s'>login</a> with mycreditdash to continue.", $configs->mcd_url);
                    wp_redirect(site_url() . "/cb-messages");
                    exit();
                }
            }
        }

        if(is_page('demo') || is_page('demo-questions') || is_page('demo-thankyou')){
            global $isFunnelLog;
            $isFunnelLog = "http://wp.creditberry.com/wp-content/uploads/2019/12/logo-placeholder.png";
        }

        // if (is_page('login')) {
        //     if (isset($_SESSION['userId']) && isset($_SESSION['userHomeUrl']) && $_SESSION['userHomeUrl'] != "") {
        //         wp_redirect(site_url() . "/" . $_SESSION['userHomeUrl']);
        //         exit();
        //     }
        // }

        // if (is_page("customer")) {
        //     CB_CONFIGS::is_user_loggedin();
        //     CB_CONFIGS::redirectToUserSpecificPage();
        // }

        // if (is_page("enduser")) {
        //     CB_CONFIGS::is_user_loggedin();
        //     CB_CONFIGS::redirectToUserSpecificPage();
        // }
    }

    /**
     * Handle login logout menu button and link
     *
     * @param array $items
     * @param array $args
     * @return array
     */

    // function login_logout_link($items, $args)
    // {
    //     if (isset($_SESSION['userId'])) {
    //         foreach ($items as $key => $item) {
    //             if ($item->title == 'Login') {
    //                 $item->title = "Logout";
    //                 $item->url = site_url() . '?logout=true';
    //             }
    //         }
    //     }
    //     return $items;
    // }


    /**
     * Get month list
     *
     * @return array
     */
    public static function getMonthList()
    {
        $months = array();
        for ($m = 1; $m <= 12; ++$m) {
            $months[str_pad($m, 2, "0", STR_PAD_LEFT)] = date('F', mktime(0, 0, 0, $m, 1));
        }
        return $months;
    }

    /**
     * Get year list
     *
     * @return array
     */
    public static function getYearList()
    {
        $y = date('Y');
        $arr = array();
        for ($i = $y; $i < $y + 20; $i++) {
            $arr[$i] = $i;
        }
        return $arr;
    }

    /**
     * Get subscription plan list
     *
     * @return array
     */
    public static function getSubscriptionPlan()
    {
        global $wpdb;
        $tbl = tblProduct;
        $result = $wpdb->get_results("SELECT * from $tbl where isActive=1");
        return $result;
    }

    /**
     * Get state list
     *
     * @return array
     */
    public static function getStateList()
    {
        global $wpdb;
        $tbl = tblState;
        $result = $wpdb->get_results("SELECT * from $tbl");
        return $result;
    }

    /**
     * Get state list
     *
     * @return array
     */
    public static function getLoginMessage()
    {
        $configs = CB_CONFIGS::configs();
        $message = sprintf("Your session has expired. Please <a href='%s'>login</a> with mycreditdash to continue.", $configs->mcd_url);
        return $message;
    }

    /**
     * Get configs 
     *
     * @return object
     */
    public static function configs()
    {
        $data = new \stdClass;
        $data->host = "tansunion.c7huddpuqtno.us-west-2.rds.amazonaws.com";
        $data->user = "logsuser";
        $data->password = "l;V{9,wi0sz?\\;C";
        $data->database = "logs";
        $data->middleware_log_table = "middleware_log";
        $data->app_log_table = "app_log";
        $data->api_url = "https://api.rapidcreditreports.com/v1/";
        $data->aws_ca_cert = "/var/www/html/creditberry/rds-combined-ca-bundle.pem";
        /*$data->authnet_login_id = "77Sr9JnHpV";
        $data->authnet_transaction_key = "4sB888q65dFc2PB2";
        $data->authnet_url = "https://apitest.authorize.net";*/
        $data->authnet_login_id = "3C67wBbz9hwR";
        $data->authnet_transaction_key = "376RTrM4Cs9j357u";
        $data->authnet_url = "https://apitest.authorize.net";   
        $data->mcd_url = "http://newdev.mycreditdash.com";
        $data->salt = "kjsdkfh45987dkfjhksjdhfkj466748tyewijfkjsdfhdfskjh43534534hkrlp9078984h$%#gg";
        $data->env = "dev";
        return $data;
    }

    /**
     * Get IP Address
     *
     * @return string
     */
    public static function getIPAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {;
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return apply_filters('wpb_get_ip', $ip);
    }

    /**
     * Get roles
     *
     * @return void
     */
    public static function getRoles()
    {
        return array(
            'ENDUSER' => 1,
            'CUSTOMER' => 2,
            'RCR' => 3,
            'RENTING' => 4,
            'AUTO' => 5,
            'MEDICAL' => 6,
            'PROFESSION' => 7,
            'CONTRACTOR' => 8,
            'LEGAL' => 9,
            'EVALUATION' => 10,
            'FINANCIAL' => 11,
        );
    }

    /**
     * Start user session
     *
     * @param integer $userId
     * @return void
     */
    public static function startUserSession($userId)
    {

        $data = CB_CONFIGS::getSessionDataByUserId($userId);

        $_SESSION['userId'] = $userId;
        $_SESSION['firstName'] = $data->firstName;
        $_SESSION['lastName'] =  $data->lastName;
        $_SESSION['accessLevel'] =  $data->name;
        $_SESSION['accessLevelID'] =  $data->accessLevelId;
        $_SESSION['email'] =  $data->email;
        $_SESSION['dob'] =  $data->dob;
        $_SESSION['addressLine1'] =  $data->address1;
        $_SESSION['addressLine2'] =  $data->address2;
        $_SESSION['city'] =  $data->city;
        $_SESSION['stateId'] =  $data->stateId;
        $_SESSION['stateCode'] =  $data->stateCode;
        $_SESSION['zip'] =  $data->zip;
        $_SESSION['userCategoryId'] =  $data->userCategoryId;
        $_SESSION['experianPurposeType'] =  $data->experianPurposeType;
        $_SESSION['userHomeUrl'] =  $data->userCategoryId == CB_CONFIGS::USER_CATEGORY_CUSTOMER ? 'customer' : 'enduser';
        $_SESSION['lastActivity'] =  time();
    }

    /**
     * Get user session data by id
     *
     * @param integer $userId
     * @return void
     */
    public static function getSessionDataByUserId($userId)
    {
        global $wpdb;
        $tblLogin = tblLogin;
        $tblUser = tblUser;
        $tblState = tblState;
        $tblUserCategory = tblUserCategory;
        $tblUserAddress = tblAddress;
        $tblUserAccessLevel = tblUserAccessLevel;

        $result = $wpdb->get_row("SELECT $tblUser.firstName,$tblUser.lastName,$tblUser.email,$tblUser.dob,$tblUser.userCategoryId, $tblLogin.accessLevelId,$tblUserAccessLevel.name, $tblUserAddress.*,$tblState.stateCode,$tblUserCategory.experianPurposeType FROM $tblLogin JOIN $tblUser ON $tblLogin.userId=$tblUser.id JOIN $tblUserAccessLevel ON $tblLogin.accessLevelId=$tblUserAccessLevel.id JOIN $tblUserAddress ON $tblUser.addressId=$tblUserAddress.id JOIN $tblState ON $tblUserAddress.stateId=$tblState.id JOIN $tblUserCategory ON $tblUser.userCategoryId=$tblUserCategory.id where $tblUser.id=" . $userId);

        update_option('cb-getSessionDataByUserId-Query', "SELECT $tblUser.firstName,$tblUser.lastName,$tblUser.email,$tblUser.dob,$tblUser.userCategoryId, $tblLogin.accessLevelId,$tblUserAccessLevel.name, $tblUserAddress.*,$tblState.stateCode,$tblUserCategory.experianPurposeType FROM $tblLogin JOIN $tblUser ON $tblLogin.userId=$tblUser.id JOIN $tblUserAccessLevel ON $tblLogin.accessLevelId=$tblUserAccessLevel.id JOIN $tblUserAddress ON $tblUser.addressId=$tblUserAddress.id JOIN $tblState ON $tblUserAddress.stateId=$tblState.id JOIN $tblUserCategory ON $tblUser.userCategoryId=$tblUserCategory.id where $tblUser.id=" . $userId);
        update_option('cb-getSessionDataByUserId', $result);

        return $result;
    }

    public static function getSingleFunnelPlanData($funnel_id, $planId)
    {
        $planData =  WhiteLabeling::cb_get_funnel_pricing($funnel_id);
        $planData = json_decode($planData);
        $planData = $planData->Data;

        if ($planData) {
            foreach ($planData as $key => $value) {
                if ($key == $planId) {
                    return $value;
                }
            }
        } else {
            return new \stdClass;
        }
    }

    public static function checkAlreadyAuthenticateWithTU($post)
    {
        $post['data'] = array(
            'FirstName' => $post['firstname'],
            'LastName' => $post['lastname'],
            'DateOfBirth' => date('Y-m-d', strtotime($post['dob'])),
            'AddressLine1' => $post['address'],
            'State' => $post['state'],
            'City' => $post['city'],
            'Zipcode' => $post['zipcode'],
            'IP' => $_SERVER["REMOTE_ADDR"],
            'Ssn' => str_replace(array('-', '-'), array('', ''), $post['ssn'])
        );
        $post['segment'] = 'authenticate';
        $data = Questions::sendAnswersToTU($post);
        $error = "";
        
        if (empty($data) || !isset($data->response->status)){
            $error = "Invalid SSN";
        }else if (isset($data->response->content->tu_errors) && isset($data->response->content->tu_errors->aName) && strtolower($data->response->content->tu_errors->aName) == 'thinfile') {
            $error = "<p><strong>We'are sorry!</strong><br/>We are unable to locate the information needed to verify your identity with TransUnion&REG;.<br/>We will be in touch soon</p>";
        }else if (isset($data->response->content->authenticatedOn)){
            $error = $data->response->message;
        }else if(isset($data->response->content->tu_errors)){
            $error = "TU error : ".is_array($data->response->content->tu_errors->aMessage) ? implode('. ', $data->response->content->tu_errors->aMessage) : $data->response->content->tu_errors->aMessage;
        }else if(isset($data->response->content->errors)){
            $error = is_array($data->response->content->errors) ? implode('. ', $data->response->content->errors) : $data->response->content->errors;
        }
        return $error;
    }

    /**
     * Check user is already authenticated with TU
     *
     * @return void
     */
    public static function checkUserAutenticatedWithTU()
    {
        global $wpdb;
        if (isset($_SESSION['userId'])) {
            $tblUserToken = tblUserToken;
            $userToken = $wpdb->get_row("SELECT * from $tblUserToken where userId=" . $_SESSION['userId']);
            if (!$userToken) {
                return false;
            } else {
                return true;
            }
        }
    }

    public static function redirectToUserSpecificPage()
    {
        if (isset($_SESSION['userId'])) {
            $userToken = CB_CONFIGS::checkUserAutenticatedWithTU();
            $configs = CB_CONFIGS::configs();
            if ($userToken) {
                if (isset($_SESSION['userHomeUrl']) && $_SESSION['userHomeUrl'] != "") {
                    $_SESSION['cb_info'] = sprintf("You are already authenticate.Please <a href='%s'>login</a> with mycreditdash to continue.", $configs->mcd_url);
                    wp_redirect(site_url() . "/cb-messages");
                    exit();
                }
            } else {
                wp_redirect(site_url() . "/complete");
                exit();
            }
        }
    }

    /**
     * Redirect to login if session is not set
     *
     * @return boolean
     */
    public static function is_user_loggedin()
    {
        if (!isset($_SESSION['userId'])) {
            $_SESSION['cb_error'] = CB_CONFIGS::getLoginMessage();
            wp_redirect(site_url() . "/cb-messages");
            exit();
        }
    }

    /**
     * Check user session
     *
     * @return void
     */
    public static function sessionCheck()
    {
        global $wpdb;

        if (isset($_SESSION['userId'])) {
            $tblUser = tblUser;

            $userToken = CB_CONFIGS::checkUserAutenticatedWithTU();

            if (!$userToken) {
                $_SESSION['cb_error'] = "You have not yet been authenticated. Please fill-in the form below.";
                wp_redirect(site_url() . "/complete");
                exit();
            } else {

                $userData = $wpdb->get_row("SELECT * from $tblUser where id=" . $_SESSION['userId']);

                if (isset($_SESSION['lastActivity']) && (time() - $_SESSION['lastActivity'] > 1200)) {

                    session_destroy();
                    session_unset();
                } else {

                    $publishDate = DateTime::createFromFormat('Y-m-d H:i:s', '2018-03-08 00:00:00');
                    $userCreatedDate = DateTime::createFromFormat('Y-m-d H:i:s', $userData->dateCreated);

                    if (($publishDate < $userCreatedDate) && $userData->authstatus == 1) {
                        session_destroy();
                        session_unset();

                        $ref = "login";
                        $uuid = $userData->clientKey;

                        Questions::thankYou($ref, $uuid);
                    }

                    $_SESSION['lastActivity'] = time();

                    if (!$_SESSION['timeCreated']) {
                        $_SESSION['timeCreated'] = time();
                    } elseif (time() - $_SESSION['timeCreated'] > 1800) {
                        session_regenerate_id(true);
                        $_SESSION['timeCreated'] = time();
                    }
                }
            }
        } else {
            $_SESSION['cb_error'] = CB_CONFIGS::getLoginMessage();
            wp_redirect(site_url() . "/cb-messages");
            exit();
        }
    }

    /**
     * Send MCD welcome email
     *
     * @param object $userData
     * @return void
     */
    public static function sendMCDWelcomeEmail($userData)
    {
        $configs = CB_CONFIGS::configs();

        $data = new stdClass();
        $data->subject = 'Welcome to Credit Berry';
        $data->to = $userData->email;
        $data->firstName = $userData->firstName;
        $data->message = CB_CONFIGS::getMCDWelcomeEmail($data, $configs->mcd_url);
        CB_CONFIGS::EmailNotifications($data);
    }

    /**
     * Send emails
     *
     * @param object $e
     * @return void
     */
    public static function EmailNotifications($e)
    {
        $to = $e->to;
        $subject = $e->subject;
        $body = $e->message;
        $headers = array('Content-Type: text/html; charset=UTF-8');

        $response = wp_mail($to, $subject, $body, $headers);
        return $response;
    }

    /**
     * Get MCD welcome email content
     *
     * @param object $data
     * @param string $url
     * @return void
     */
    public static function getMCDWelcomeEmail($data, $url)
    {
        $subject = $data->subject;
        $message = ""
            . "<p>Welcome [FIRSTNAME],</p>"
            . "<p>Thank you for your interest in Credit Berry.</p>"
            . "<p>Please click the link below to log into your account.</p>"
            . "<p><a href='[MCD_LINK]'>[MCD_LINK]</a></p>"
            . "<p>Email address : [EMAIL]</p>"
            . "<p>Password : <i>Same as you used at the time of registration </i></p>";

        $message = str_replace('[FIRSTNAME]', $data->firstName, $message);
        $message = str_replace('[MCD_LINK]', $url, $message);
        $message = str_replace('[EMAIL]', $data->to, $message);

        return CB_CONFIGS::setEmailTemplate($message, $subject);
    }

    /**
     * Set email template
     *
     * @param string $message
     * @param string $subject
     * @return string
     */
    public static function setEmailTemplate($message, $subject)
    {
        $template = '<html> <head> <title>Email</title> </head> <body> <table> <tbody> <tr> <td> <table border="0" cellspacing="0" cellpadding="0" style="max-width:600px; "> <tbody> <tr> <td> <table width="100%" border="0" cellspacing="0" cellpadding="0" > <tbody> <tr> <td align="left"><img src="[COMPANY_LOGO]" style="display:block"></td></tr></tbody> </table> </td></tr><tr height="16"></tr><tr> <td> <table bgcolor="#126F39" width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:332px;max-width:600px;border:1px solid #e0e0e0;border-bottom:0;border-top-left-radius:3px;border-top-right-radius:3px;background-color: #076799;" > <tbody> <tr> <td height="18px" colspan="3"></td></tr><tr> <td width="32px"></td><td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:24px;color:#ffffff;line-height:1.25">[SUBJECT]</td><td width="32px"></td></tr><tr> <td height="18px" colspan="3"></td></tr></tbody> </table> </td></tr><tr> <td> <table bgcolor="#FAFAFA" width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:332px;max-width:600px;border:1px solid #f0f0f0;border-bottom:1px solid #c0c0c0;border-top:0;border-bottom-left-radius:3px;border-bottom-right-radius:3px"> <tbody> <tr height="16px"> <td width="32px" rowspan="3"></td><td></td><td width="32px" rowspan="3"></td></tr><tr> <td> <table style="min-width:300px" border="0" cellspacing="0" cellpadding="0"> <tbody> <tr> <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:13px;color:#202020;line-height:1.5">[MESSAGE]</td></tr><tr height="32px"></tr><tr> <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:13px;color:#202020;line-height:1.5">Best,<br>The [COMPANY_NAME] team</td></tr><tr height="16px"></tr><tr> <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:12px;color:#b9b9b9;line-height:1.5">NOTICE - This communication may contain confidential and privileged information that is for the sole use of the intended recipient. Any viewing, copying or distribution of, or reliance on this message by unintended recipients is strictly prohibited. If you have received this message in error, please notify us immediately by replying to the message and deleting it from your computer.<br/><br/></td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr><tr height="10px"></tr></tbody> </table> <tr height="16"></tr><tr> <td style="text-align: center">Credit Berry | Pasadena, California | 888.955.8520</td><!--<td style="max-width:600px;font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:10px;color:#bcbcbc;line-height:1.5"> You received this mandatory email service announcement to update you about important changes to your [COMPANY_NAME] or account.<br><div style="direction:ltr;text-align:left">&copy; [DATE] [COMPANY_NAME], [COMPANY_ADDRESS]</div></td>--> </tr></td></tr></tbody> </table> </body></html>';

        $template = str_replace('[COMPANY_LOGO]', get_template_directory_uri() . "/images/logo-small.png", $template);
        $template = str_replace('[DATE]', date('Y'), $template);
        $template = str_replace('[SUBJECT]', $subject, $template);
        $template = str_replace('[MESSAGE]', $message, $template);
        $template = str_replace('[COMPANY_NAME]', CB_CONFIGS::COMPANY_NAME, $template);
        $template = str_replace('[COMPANY_ADDRESS]', CB_CONFIGS::COMPANY_ADDRESS, $template);

        return $template;
    }

    /**
     * Check user is activated
     *
     * @param string $time
     * @param integer $id
     * @return boolean
     */
    public static function isActived($time, $id)
    {
        global $wpdb;
        $tblLogin = tblLogin;
        if (empty($time))
            return false;
        $diff = (((strtotime(date('Y-m-d H:i:s')) - strtotime($time))) / (60 * 60 * 24));
        if ($diff && $diff >= 24) {
            $updated =  $wpdb->update(
                $tblLogin,
                array('isActive' => 1, 'deactivateon' => NULL),
                array('id' => $id)
            );
            if ($updated) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Check login attempts
     *
     * @param integer $user_id
     * @param integer $status
     * @param string $mode
     * @return void
     */
    public static function loginAttemps($user_id = null, $status = 0, $mode = 'track')
    {
        global $wpdb;
        $tblLogin = tblLogin;
        $tblLoginattemps = tblLoginAttempts;
        $session_id = wp_get_session_token();
        $attempts = $wpdb->get_results("SELECT * from $tblLoginattemps where session_id='$session_id' AND user_id=$user_id AND status=0");
        if ($mode == 'track' && $user_id) :

            $wpdb->insert($tblLoginattemps, array(
                'user_id' => $user_id,
                'session_id' => $session_id,
                'status' => $status
            ));
        elseif ($mode == 'islocked') :
            if (is_array($attempts) && count($attempts) >= 3) :
                $updated =  $wpdb->update(
                    $tblLogin,
                    array('isActive' => 0, 'deactivateon' => date('Y-m-d H:i:s')),
                    array('userId' => $user_id)
                );
            endif;
        else :
            return $attempts ? count($attempts) : 0;
        endif;
    }

    /**
     * Create admin menu pages
     *
     * @return void
     */
    public function cb_admin_menus()
    {
        add_menu_page(__("Creditberry"), __("Creditberry"), 'manage_options', 'cb', array($this, 'load_template'), 'dashicons-list-view');
        add_submenu_page("cb", __('Email Templates'), __('Email Templates'), 'manage_options', "cb-emails", array($this, 'load_template'));
    }

    /**
     * Loads template file
     *
     * @since 1.0.0
     */
    function load_template()
    {
        if (isset($_GET['page']) && $_GET['page'] == "cb-emails") {
            require get_template_directory() . '/inc/classes/templates.php';
            if (isset($_GET['edit']) && $_GET['edit'] != "") {
                $data = CB_Email_Templates::getSingleEmailTemplateDetails($_GET['edit']);
                if ($data) {
                    require get_template_directory() . '/page-template/admin/email-edit.php';
                } else {
                    goto notFoundData;
                }
            } else {
                notFoundData: $data = new CB_Email_Templates();
                $data->prepare_items();
                require get_template_directory() . '/page-template/admin/email-list.php';
            }
        }
    }
}

new CB_CONFIGS();
