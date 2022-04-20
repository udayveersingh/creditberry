<?php

use App\Models\Config;

class Questions
{
    public function __construct()
    {
        add_action('template_redirect', array($this, 'checkRedirections'));
    }

    /**
     * Check redirections
     *
     * @return void
     */
    public function checkRedirections()
    {
        if (is_page('questions')) {

            // redirect to LOGIN page is not loggedin
            CB_CONFIGS::is_user_loggedin();

            // submit selected questions
            $this->handleQuestionFormSubmission();

            // is user is already registered then redirect to its main page
            $this->checkUserAuthToken();

            // add user tries 
            $this->addUserTries();

            // check all question attempts exceeded
            $userQuestionTries = $this->checkIfAllowedAttemptsExceeded();

            // connection to TU
            $this->connectToTu($userQuestionTries);
        }
    }

    /**
     * Handle question form submission
     *
     * @return void
     */
    public function handleQuestionFormSubmission()
    {
        // if question form submits

        global $wpdb;
        $tblUser = tblUser;
        $tblLogin = tblLogin;
        $tblOrder = tblOrder;
        $tblProduct = tblProduct;
        $tblUserQuestionTries = tblUserQuestionTries;
        $tblAddress = tblAddress;
        $tblState = tblState;
        $tblUserToken = tblUserToken;

        if (isset($_POST['authenticate-questions']) && !empty($_POST['authenticate-questions'])) {

            $userData = $wpdb->get_row("SELECT $tblUser.*, $tblOrder.response, $tblOrder.totalAmount,$tblOrder.dateCreated as orderDate, $tblProduct.alias, $tblAddress.address1,$tblAddress.city,$tblAddress.zip, $tblLogin.raw_pass,$tblState.stateCode FROM $tblUser INNER JOIN $tblOrder ON $tblUser.id=$tblOrder.userId INNER JOIN $tblProduct ON $tblOrder.productId=$tblProduct.id INNER JOIN $tblAddress ON $tblUser.addressId=$tblAddress.id INNER JOIN $tblLogin ON $tblUser.id=$tblLogin.userId INNER JOIN $tblState ON $tblAddress.stateId=$tblState.id where $tblUser.id = " . $_SESSION['userId']);
            update_option('cb-final-mcd-data', $userData);
            $post = array();
            $x_data = '<?xml version="1.0" encoding="utf-8"?>';
            $x_data .= '<ArrayOfVerifyChallengeAnswersRequestMultiChoiceQuestion xmlns="com/truelink/ds/sch/srv/iv/ccs">';

            foreach ($_POST['QuestionId'] as $index => $q) :
                ++$index;
                $x_data .= "<VerifyChallengeAnswersRequestMultiChoiceQuestion>";
                $x_data .= "<QuestionId>{$q}</QuestionId>";
                $x_data .= "<SelectedAnswerChoice>";
                $x_data .= "<AnswerChoiceId>" . $_POST['AnswerChoiceId' . $index] . "</AnswerChoiceId>";
                $x_data .= "</SelectedAnswerChoice>";
                $x_data .= "</VerifyChallengeAnswersRequestMultiChoiceQuestion>";
            endforeach;
            $x_data .= '</ArrayOfVerifyChallengeAnswersRequestMultiChoiceQuestion>';

            $post['data']['ClientKey'] = $userData->clientKey;
            $post['data']['Answers'] = $x_data;
            $post['segment'] = 'verifyanswers';


            $questionResponseData = Questions::sendAnswersToTU($post);

            $userQuestionTries = $this->checkIfAllowedAttemptsExceeded();
            $_q = array();


            $log = new stdClass();
            $log->body = json_encode(['ClientKey' => $userData->clientKey, 'xml' => $x_data]);
            $log->event = 'registration:step3:verifyanswers';
            $log->user_id = $_SESSION['userId'];


            if (!isset($questionResponseData->response->status) || empty($questionResponseData->response->status)) :
                $_SESSION['cb_error'] = 'We are sorry! Something went wrong, please try again.';

                #log
                $log->status = 'failed';
                $log->exception = "Api didn't responded with correct status";
                $log = new CB_Logger($log->body, $log->event, $log->status, $log->exception, $log->user_id);

                wp_redirect(site_url() . "/complete");
                exit();
            endif;

            switch ($questionResponseData->response->status):
                case 'failed':

                    #log
                    $log->status = 'failed';
                    $log->exception = "verification failed";
                    $log = new CB_Logger($log->body, $log->event, $log->status, $log->exception, $log->user_id);

                    if (isset($questionResponseData->response->content->verification_status) && $questionResponseData->response->content->verification_status == 'incorrect') :
                        $userQuestionTries->numberOfTries -= 1;
                        $wpdb->update(
                            $tblUserQuestionTries,
                            array(
                                'numberOfTries' => $userQuestionTries->numberOfTries,
                            ),
                            array('userId' => $_SESSION['userId'])
                        );
                    endif;

                    $_e = "{$questionResponseData->response->content->errors}";
                    if (isset($questionResponseData->response->content->tu_errors) && !empty($questionResponseData->response->content->tu_errors)) :
                        $_e .= is_array($questionResponseData->response->content->tu_errors) ? implode('. ', $questionResponseData->response->content->tu_errors) : $questionResponseData->response->content->tu_errors;
                    endif;

                    $_SESSION['authenticate'] = false;
                    $_SESSION['cb_error'] = $_e;

                    break;

                case 'success':

                    if ($questionResponseData->response->content->verification_status == 'inprogress') :

                        $_q['r_type'] = 'process';
                        $_q['questions'] = $this->xml2Object($questionResponseData->response->content->questions);
                        $_q['authSession'] = $questionResponseData->response->content->ClientKey;
                        $_SESSION['authenticate'] = $_q;
                        $_SESSION['cb_info'] = $questionResponseData->response->content->message;
                        $_SESSION['cb_info_inprogress'] = 1;

                    else :

                        $wpdb->update(
                            $tblUser,
                            array(
                                'third_step' => date('Y-m-d h:i:s'),
                                'authstatus' => 1,
                            ),
                            array('id' => $_SESSION['userId'])
                        );

                        $addressData = $wpdb->get_row("SELECT * from $tblAddress where id=" . $userData->addressId);
                        $stateData = $wpdb->get_row("SELECT * from $tblState where id=" . $addressData->stateId);

                        unset($_SESSION['ssn']);

                        $wpdb->insert(
                            $tblUserToken,
                            array(
                                'userId' => $_SESSION['userId'],
                                'userTokenHash' => md5($_SESSION['userId'] . time()),
                                'tokenIssuedDate' => date("Y-m-d H:i:s"),
                                'canViewOwnReport' => 1
                            )
                        );

                        $this->resetUserQuestionTries();

                        $_SESSION['cb_success'] = 'Operation successful. Account successfully authenticated, Please check your email for further instructions.';

                        $plan = $wpdb->get_row("SELECT * from $tblProduct where id=" . $_SESSION['plan']);
                        if (isset($_SESSION['funnel_id'])) {
                            $plan =  CB_CONFIGS::getSingleFunnelPlanData($_SESSION['funnel_id'], $_SESSION['plan']);
                        }
                        $mcdData = [
                            'user' => [
                                'client_key' => $userData->clientKey,
                                'funnel_id' => isset($_SESSION['funnel_id']) ? $_SESSION['funnel_id'] : $plan->mcd_funnel_id,
                                'affiliate_id' => $plan->mcd_affiliate_id,
                                'firstname' => $userData->firstName,
                                'lastname' => $userData->lastName,
                                'email' => $userData->email,
                                'password' => $userData->raw_pass ?: 'demo@123',
                                'street' => $userData->address1,
                                'city' => $userData->city,
                                'state' => $userData->stateCode,
                                'dob' => $userData->dob,
                                'zip' => $userData->zip,
                                'is_authenticated' => 1,
                                'created_at' => $userData->dateCreated,
                                'updated_at' => $userData->dateCreated,
                                'is_whitelabel' => $userData->is_whitelabel,
                            ],
                            'order' => $userData && $userData->response ? array_merge(json_decode($userData->response, true), ['amount_paid' => $userData->totalAmount]) : null,
                            'product' => $userData && $userData->alias ? $userData->alias : null
                        ];

                        $mcdData['order']['created_at'] = $userData ? $userData->orderDate : date('Y-m-d H:i:s', time());
                        $mcdData['order']['updated_at'] = $userData ? $userData->orderDate : date('Y-m-d H:i:s', time());

                        if (isset($_SESSION['uuid']) && !empty($_SESSION['uuid'])) {
                            $mcdData['user']['uuid'] = $_SESSION['uuid'];
                        }

                        update_option('cb-from-last-mcd-data', $mcdData);
                        $response = $this->saveToMcd($mcdData);

                        CB_CONFIGS::sendMCDWelcomeEmail($userData);

                        $wpdb->update(
                            $tblLogin,
                            array('raw_pass' => null),
                            array('userId' => $_SESSION['userId'])
                        );

                        $ref = "auth";
                        $uuid = $userData->clientKey;

                        // forward to thank you action/page
                        Questions::thankYou($ref, $uuid);
                    endif;

                    break;
            endswitch;
            wp_redirect(site_url() . "/questions");
            exit();
        }
    }

    /**
     * Redirection on thank you OR MCD after successfull registration
     *
     * @param string $ref
     * @param string $uuid
     * @return void
     */
    public static function thankYou($ref, $uuid)
    {
        global $wpdb;
        if (!$uuid) {
            wp_redirect(site_url());
            exit();
        }

        // $tblProduct = tblProduct;
        // $_SESSION['cb_info'] = 'You have been authenticated, Please follow below link to get started.';

        // $plan = $wpdb->get_row("SELECT * from $tblProduct where id=" . $_SESSION['plan']);

        $configs = CB_CONFIGS::configs();
        $redirect_url = $configs->mcd_url . '/get_started/mcd/' . $uuid;
        session_destroy();
        session_unset();
        // if ($plan->auto_login) {
        wp_redirect($redirect_url);
        exit();
        // } else {
        //     $_SESSION['thankyou']["ref"] = $ref;
        //     $_SESSION['thankyou']["uuid"] = $uuid;
        //     $_SESSION['thankyou']["redirect"] = $redirect_url;
        //     $_SESSION['cb_info'] = 'You have been authenticated, Please follow below link to get started.';
        //     wp_redirect(site_url() . "/thank-you");
        //     exit();
        // }
    }

    /**
     * Check user auth token
     *
     * @return void
     */
    public function checkUserAuthToken()
    {
        $token = CB_CONFIGS::checkUserAutenticatedWithTU();

        if ($token) {
            $_SESSION['cb_error'] = "Your account is already authenticated with TU.";

            if (isset($_SESSION['userHomeUrl']) && $_SESSION['userHomeUrl'] != "") {
                wp_redirect(site_url() . "/" . $_SESSION['userHomeUrl']);
                exit();
            }
        }
    }

    /**
     * Insert user question tries
     *
     * @return void
     */
    public function addUserTries()
    {
        global $wpdb;
        $tblUserQuestionTries = tblUserQuestionTries;

        $tries = $wpdb->get_row("SELECT * from $tblUserQuestionTries where userId=" . $_SESSION['userId']);

        if (!$tries) {
            $wpdb->insert(
                $tblUserQuestionTries,
                array(
                    'userId' => $_SESSION['userId'],
                    'isLocked' => 0,
                    'numberOfTries' => CB_CONFIGS::USER_QUESTION_NUMBER_OF_TRIES
                )
            );
        }
    }

    /**
     * Check if all question try attempts are exceeded
     *
     * @return void
     */
    public function checkIfAllowedAttemptsExceeded()
    {
        global $wpdb;
        $tblUserQuestionTries = tblUserQuestionTries;
        $userQuestionTries = $wpdb->get_row("SELECT * from $tblUserQuestionTries where userId = " . $_SESSION['userId']);

        if ($userQuestionTries->numberOfTries == CB_CONFIGS::INACTIVE) {

            $wpdb->update(
                $tblUserQuestionTries,
                array('isLocked' => 1, 'lockCreatedDate' => date('Y-m-d H:i:s')),
                array('userId' => $_SESSION['userId'])
            );

            $_SESSION['cb_error'] = 'You have exceeded the number of retry attempts for the authentication questions. Please wait for a total of ' . CB_CONFIGS::USER_QUESTION_NEXT_RETRY_DAYS . ' days before you can retry again.';

            #log
            $log = new stdClass();
            $log->body = json_encode(['ssn' => $_SESSION['ssn']]);
            $log->event = 'registration:step3:tries exceeded';
            $log->user_id = $_SESSION['userId'];
            $log->status = 'failed';
            $log->exception = 'number of tries exceeded';
            $log = new CB_Logger($log->body, $log->event, $log->status, $log->exception, $log->user_id);

            wp_redirect(site_url() . "/cb-messages");
            exit();
        }
        return $userQuestionTries;
    }

    /**
     * Connect to TU - Transunion
     *
     * @param integer $userQuestionTries
     * @return void
     */
    public function connectToTu($userQuestionTries)
    {
        global $wpdb;
        $tblUser = tblUser;

        // redirect to show message
        if (!$userQuestionTries->numberOfTries) :
            wp_redirect(site_url() . "/cb-messages");
            exit();
        endif;

        if (isset($_SESSION['authenticate']) && !empty($_SESSION['authenticate']) && is_array($_SESSION['authenticate'])) {
            $data = $_SESSION['authenticate'];
        } else {
            $data = $this->getQuestions();
        }

        $log = new stdClass();
        $log->body = json_encode(['ssn' => $_SESSION['ssn']]);
        $log->event = 'registration:step3:getquestions';
        $log->user_id = $_SESSION['userId'];

        if (isset($data['questions']) && !empty($data['questions'])) {

            #log
            $log->status = 'success';
            $log->exception = null;
            $log = new CB_Logger($log->body, $log->event, $log->status, $log->exception, $log->user_id);

            $userData = $wpdb->get_row("SELECT * from $tblUser where id=" . $_SESSION['userId']);
            $wpdb->update(
                $tblUser,
                array('clientKey' => $data['authSession']),
                array('id' => $_SESSION['userId'])
            );

            if (isset($data['questions']->ChallengeConfiguration)) :
                $quest = $data['questions']->ChallengeConfiguration->MultiChoiceQuestion;
            else :
                $quest = $data['questions']->MultiChoiceQuestion;
            endif;

            if (isset($_SESSION['authError']) && $_SESSION['authError'] == true) {
                unset($_SESSION['authError']);
            }

            if (!is_array($quest)) {
                $quest = [$quest];
            }

            $questionArr =  array(
                'questions' => $quest,
                'authSession' => $data['authSession'],
                'triesCount' => $userQuestionTries->numberOfTries,
                'userId' => $_SESSION['userId'],
                'userData' => $userData
            );

            global $wp_query;
            if ($wp_query->is_404) {
                $wp_query->is_404 = false;
                $wp_query->is_archive = true;
            }

            header("HTTP/1.1 200 OK");
            require(get_template_directory() . '/page-template/questions.php');
            exit;
        } else {
            #log
            $log->status = 'failed';
            $log->exception = "Failed to get authentication questions";
            $log = new CB_Logger($log->body, $log->event, $log->status, $log->exception, $log->user_id);

            if (!isset($data['questions']) || empty($data['questions'])) :
                $_SESSION['cb_error'] = "We are sorry! Something went wrong, please try again.";
            endif;

            if (isset($data['error'])) :

                #log
                $log->status = $data['status_code'];
                $log->exception = $data['error'];
                $log = new CB_Logger($log->body, $log->event, $log->status, $log->exception, $log->user_id);

                $_SESSION['cb_error'] = $data['error'];
                $_SESSION['status_code'] = $data['status_code'];
                update_option("cb-question-failed-block", $data);
            endif;

            $_SESSION['authError'] = true;
            wp_redirect(site_url() . "/complete");
            exit();
        }
    }

    /**
     * Get questions from TU
     *
     * @return void
     */
    function getQuestions()
    {

        global $wpdb;
        $tblUser = tblUser;
        $tblAddress = tblAddress;
        $tblState = tblState;

        $post = array();

        $userData = $wpdb->get_row("SELECT $tblUser.*,$tblAddress.address1,$tblAddress.city,$tblAddress.zip, $tblState.stateCode FROM $tblUser INNER JOIN $tblAddress ON $tblUser.addressId=$tblAddress.id INNER JOIN $tblState ON $tblAddress.stateId=$tblState.id where $tblUser.id = " . $_SESSION['userId']);

        $post['data'] = array(
            'FirstName' => $userData->firstName,
            'LastName' => $userData->lastName,
            'DateOfBirth' => date('Y-m-d', strtotime($userData->dob)),
            'AddressLine1' => $userData->address1,
            'State' => $userData->stateCode,
            'City' => $userData->city,
            'Zipcode' => $userData->zip,
            'IP' => $_SERVER["REMOTE_ADDR"]
        );

        if (!empty($userData->clientKey)) :
            $post['data']['ClientKey'] = $userData->clientKey;
        else :
            $post['data']['Ssn'] = str_replace(array('-', '-'), array('', ''), $_SESSION['ssn']);
        endif;

        $post['segment'] = 'authenticate';


        $data = Questions::sendAnswersToTU($post);

        if (isset($data->response->content->tu_errors) && isset($data->response->content->tu_errors->aName) && strtolower($data->response->content->tu_errors->aName) == 'thinfile') {
            $_SESSION['cb_error'] = "<h5>We'are sorry!</h5><h6>We are unable to locate the information needed to verify your identity with TransUnion&REG;.</h6><p>We will be in touch soon</p>";
            wp_redirect(site_url() . "/cb-messages");
            exit();
        }

        if (isset($data->response->content->authenticatedOn)) :
            $_SESSION['cb_info'] = $data->response->message;
            wp_redirect(site_url() . "/cb-messages");
            exit();
        endif;

        if (empty($data) || !isset($data->response->status)) :
            $_SESSION['cb_error'] = "Invalid SSN";
            wp_redirect(site_url() . "/complete");
            exit();
        endif;


        $_q = array();
        $_q['status_code'] = $data->response->status_code;

        switch ($data->response->status):
            case 'success':

                $wpdb->update(
                    $tblUser,
                    array('clientKey' => $data->response->content->ClientKey),
                    array('id' => $_SESSION['userId'])
                );

                $_q['r_type'] = 'load';
                $_q['questions'] = null;

                if (isset($data->response->content->questions) && !empty($data->response->content->questions)) :
                    $_q['questions'] = $this->xml2Object(Questions::strip_cdata($data->response->content->questions));
                endif;

                $_q['authSession'] = $data->response->content->ClientKey;

                break;
            default:

                $_q['error'] = is_array($data->response->content->errors) ? implode('. ', $data->response->content->errors) : $data->response->content->errors;
                if (isset($data->response->content->tu_errors)) :
                    $_q['error'] .= is_array($data->response->content->tu_errors->aMessage) ? implode('. ', $data->response->content->tu_errors->aMessage) : $data->response->content->tu_errors->aMessage;
                endif;
                if ($data->response->status_code == 122) :
                    $_q['error'] = "Please enter your complete 9 digit SSN as we are having a difficult time location your file";
                endif;
                break;
        endswitch;

        return $_q;
    }

    /**
     * Convert XML to Object 
     *
     * @param string $xml_string
     * @return void
     */
    public function xml2Object($xml_string)
    {
        preg_match_all('/<!\[cdata\[(.*?)\]\]>/is', $xml_string, $matches);
        $xml_string = str_replace($matches[0], $matches[1], $xml_string);
        $xml = json_decode(json_encode(@simplexml_load_string($xml_string)));
        return $xml;
    }

    /**
     * Strip CData
     *
     * @param string $string
     * @return void
     */
    public static function strip_cdata($string)
    {
        preg_match_all('/<!\[cdata\[(.*?)\]\]>/is', $string, $matches);
        return str_replace($matches[0], $matches[1], $string);
    }

    /**
     * Send selected answers to TU
     *
     * @param array $request
     * @param boolean $debug
     * @return void
     */
    public static function sendAnswersToTU($request, $debug = false)
    {
        $api_config = CB_CONFIGS::configs();
        $headers = array();
        $host = "{$api_config->api_url}" . $request['segment'];

        $headers[] = ($debug == true) ? 'Accept: application/xml' : 'Accept: application/json';
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $host);
        curl_setopt($curl_handle, CURLOPT_POST, true);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($request['data']));
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);
        if ($debug == true) {
            header("Content-Type: application/xml");
            echo $response;
            die;
        }
        return json_decode($response);
    }

    /**
     * Reset question tries
     *
     * @return void
     */
    public function resetUserQuestionTries()
    {
        global $wpdb;
        $tblUserQuestionTries = tblUserQuestionTries;
        $wpdb->update(
            $tblUserQuestionTries,
            array(
                'numberOfTries' => CB_CONFIGS::USER_QUESTION_NUMBER_OF_TRIES,
                'isLocked' => CB_CONFIGS::INACTIVE,
                'lockCreatedDate' => null
            ),
            array('userId' => $_SESSION['userId'])
        );
        $userQuestionTries = $wpdb->get_row("SELECT * from $tblUserQuestionTries where $tblUserQuestionTries.id = " . $_SESSION['userId']);

        return $userQuestionTries;
    }

    /**
     * Save userdata to MCD
     *
     * @param array $data
     * @return void
     */
    function saveToMcd($data)
    {

        $logConfig = CB_CONFIGS::configs();
        $funnelUrl = $logConfig->mcd_url;
        $funnelUrl .= '/funnel-api/user/create';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $funnelUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, 1);
        $result = curl_exec($ch);
        echo curl_error($ch);
        curl_close($ch);

        return $result;
    }
}
new Questions();
