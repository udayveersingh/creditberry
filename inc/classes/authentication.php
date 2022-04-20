<?php

class CB_AUTHENTICATION
{
    function  __construct()
    {
        // add_action("init", array($this, 'cb_login'));
        // add_action("init", array($this, 'cb_logout'));
    }

    /**
     * Process Login
     *
     * @return void
     */
    public function cb_login()
    {
        if (isset($_POST['btn-cb-login'])) {
            if (isset($_POST['processLoginNonce']) && wp_verify_nonce($_POST['processLoginNonce'], 'processLoginActionNonce')) {
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);
                if ($username != "" && $password != "") {

                    $errorCode = $this->authenticate($username, $password);
                    if (CB_CONFIGS::ERROR_NONE === $errorCode) {
                        if ($_SESSION['accessLevelID'] == CB_CONFIGS::ACCESS_LEVEL_NONE) { 

                            $data = array('body' => $_POST, 'event' => 'User Login', 'status' => 'success');

                            $body = json_encode($data['body']);
                            $event = $data['event'];
                            $status = isset($data['status']) ? $data['status'] : 'failed';
                            $exception = isset($data['excep']) ? $data['excep'] : NULL;

                            $log = new CB_Logger($body, $event, $status, $exception, $_SESSION['userId']);

                            $url = site_url()."/".$_SESSION['userHomeUrl'];
                            wp_redirect($url);
                            exit();
                        } else {
                            $_SESSION['cb_error'] = "You don't have a customer account.";
                        }
                    }
                } else {
                    $_SESSION['cb_error'] = "Username or password can not be blank";
                }
            }
        }
    }

    /**
     * Authenticate username and password while login
     *
     * @param string $username
     * @param string $password
     * @return void
     */
    function authenticate($username, $password)
    {
        global $wpdb;
        $errorCode = 0;
        $tblLogin = tblLogin;
        $loginModel = $wpdb->get_row("SELECT * from $tblLogin where username='$username'");

        if (!$loginModel) {
            $_SESSION['cb_error'] = "Incorrect username or password supplied. Please retry.";
            return $errorCode = CB_CONFIGS::ERROR_UNKNOWN_IDENTITY;
        } else if ($loginModel->isActive == 0) {
            $isActive = CB_CONFIGS::isActived($loginModel->deactivateon, $loginModel->id);
            if (empty($loginModel->deactivateon)) {
                $_SESSION['cb_error'] = "Currently your account is deactivated";
                return $errorCode = CB_CONFIGS::ERROR_ACCOUNT_DISABLED;
            } else if ($isActive) {
                CB_CONFIGS::startUserSession($loginModel->userId);
                CB_CONFIGS::loginAttemps($loginModel->userId, 1);
                return $errorCode = CB_CONFIGS::ERROR_NONE;
            }
            $_SESSION['cb_error'] = "Your have exceeded max login attempts.";
            return $errorCode = CB_CONFIGS::ERROR_ACCOUNT_LOGIN_ATTEMPTS;
        } elseif (md5($password) !== $loginModel->password) {
            CB_CONFIGS::loginAttemps($loginModel->userId, 1);
            CB_CONFIGS::loginAttemps($loginModel->userId, $loginModel->id, 'islocked');
            $_SESSION['cb_error'] = "Incorrect username or password supplied. Please retry.";
            return $errorCode = CB_CONFIGS::ERROR_PASSWORD_INVALID;
        } else {
            CB_CONFIGS::startUserSession($loginModel->userId);
            CB_CONFIGS::loginAttemps($loginModel->userId, 1);
            return $errorCode = CB_CONFIGS::ERROR_NONE;
        }
        return $errorCode = CB_CONFIGS::ERROR_UNKNOWN_IDENTITY;
    }

    /**
     * Logout user and destroy session
     *
     * @return void
     */
    function cb_logout()
    {
        if (isset($_GET['logout']) && $_GET['logout'] == true) {
            session_destroy();
            session_unset();
            wp_redirect(site_url() . "/login");
            exit();
        }
    }
}

new CB_AUTHENTICATION();
