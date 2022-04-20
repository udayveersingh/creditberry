<?php
require_once(get_template_directory() . '/lib/authorizenet/vendor/autoload.php');
require_once(get_template_directory() . '/lib/authorizenet/vendor/constants.php');

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use net\Constants;

class PaymentHelper
{
    public function __construct()
    {

    }

    /**
     * Make payment
     *
     * @param array $post
     * @param object $user
     * @param boolean $recurring
     * @return void
     */
    public static function makePayment($post = [], $user = null, $recurring = false)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $configs = CB_CONFIGS::configs();

        if (!is_null($user)) {
            // get user data from existing database table
        } else {

            $firstName = $post['firstname'];
            $lastName = $post['lastname'];
            $email = $post['email'];
            $cardNumber = (int) $post['ccard'];
            $cardExpiry = $post['cardYear'] . "-" . $post['cardMonth'];
            $cardCvv = $post['cvv'];
            $address = $post['address'];
            $city = $post['city'];
            $state = $post['state'];
            $zip = $post['zip'];
        }

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($configs->authnet_login_id);
        $merchantAuthentication->setTransactionKey($configs->authnet_transaction_key);
        // Set the transaction's refId
        $refId = 'ref' . time();
        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($cardExpiry);
        $creditCard->setCardCode($cardCvv);

        if ($recurring == false) {
            // Add the payment data to a paymentType object
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);

            // Create order information
            $order = new AnetAPI\OrderType();
            $order->setInvoiceNumber(uniqid());
            $order->setDescription("CB Payment");

            // Set the customer's Bill To address
            $customerAddress = new AnetAPI\CustomerAddressType();
            $customerAddress->setFirstName($firstName);
            $customerAddress->setLastName($lastName);
            $customerAddress->setAddress($address);
            $customerAddress->setCity($city);
            $customerAddress->setState($state);
            $customerAddress->setZip($zip);
            $customerAddress->setCountry("USA");

            // Set the customer's identifying information
            $customerData = new AnetAPI\CustomerDataType();
            $customerData->setType("individual");
            $customerData->setEmail($email);

            // Create a TransactionRequestType object and add the previous objects to it
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($post['amount']);
            $transactionRequestType->setOrder($order);
            $transactionRequestType->setPayment($paymentOne);
            $transactionRequestType->setBillTo($customerAddress);
            $transactionRequestType->setCustomer($customerData);

            // Assemble the complete transaction request
            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setTransactionRequest($transactionRequestType);

            // Create the controller and get the response
            $controller = new AnetController\CreateTransactionController($request);
        } else {
            // Subscription Type Info
            $subscription = new AnetAPI\ARBSubscriptionType();
            $subscription->setName("CB Subscription");
            $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
            $interval->setLength(30);
            $interval->setUnit("days");
            $paymentSchedule = new AnetAPI\PaymentScheduleType();
            $paymentSchedule->setInterval($interval);
            $paymentSchedule->setStartDate(new DateTime());
            $paymentSchedule->setTotalOccurrences("9999");
            $subscription->setPaymentSchedule($paymentSchedule);
            $subscription->setAmount($post['amount']);

            $payment = new AnetAPI\PaymentType();
            $payment->setCreditCard($creditCard);

            $subscription->setPayment($payment);

            $order = new AnetAPI\OrderType();
            $order->setInvoiceNumber(uniqid());
            $order->setDescription("TRANSUNION REPORT WITH SCORE AND MONITORING");

            $subscription->setOrder($order);

            $billTo = new AnetAPI\NameAndAddressType();
            $billTo->setFirstName($firstName);
            $billTo->setLastName($lastName);

            $subscription->setBillTo($billTo);

            $request = new AnetAPI\ARBCreateSubscriptionRequest();
            $request->setmerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setSubscription($subscription);

            $controller = new AnetController\ARBCreateSubscriptionController($request);
        }

        $response = $controller->executeWithApiResponse($configs->authnet_url);
       /* echo '<pre>';
        print_r($response);   */
        $msg = '';
        if ($response != null) {
            if ($response->getMessages()->getResultCode() == Constants::RESPONSE_OK) {
                $tresponse = $recurring ? $response : $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getMessages() != null) {

                    $return = [
                        'status' => 'success',
                        'message' => $recurring ? $response->getSubscriptionId() : $tresponse->getTransId()
                    ];

                    if ($recurring) {
                        //subscription response end
                        $transactionResponse = $response->getProfile();
                        $transactionCode = $response->getMessages()->getResultCode();
                        if ($transactionResponse) :
                            $return['customer'] = [
                                'profile_id' => $transactionResponse->getCustomerProfileId(),
                                'payment_profile_id' => $transactionResponse->getCustomerPaymentProfileId(),
                                'customer_address_id' => $transactionResponse->getCustomerAddressId(),
                                'transaction_status' => $transactionCode,
                                'subscription_id' => $response->getSubscriptionId(),
                                'ref_id' => $response->getRefId(),
                                'is_subscription' => 1
                            ];
                        endif;
                        //subscription response end
                    } else {
                        //transaction response start
                        $transactionResponse = $response->getTransactionResponse();
                        $transactionCode = $response->getMessages()->getResultCode();
                        $trans_id = $transactionResponse->getTransId();
                        $profile_ids = PaymentHelper::createCustomerProfileFromTransaction($trans_id, $user, $merchantAuthentication, $configs);

                        if ($transactionResponse) :
                            $return['customer'] = [
                                'trans_id' => $trans_id,
                                'trans_hash' => $transactionResponse->getTransHash(),
                                'account_no' => $transactionResponse->getAccountNumber(),
                                'account_type' => $transactionResponse->getAccountType(),
                                'message' => $transactionResponse->getMessages()[0]->getDescription(),
                                'auth_code' => $transactionResponse->getAuthCode(),
                                'avs_result_code' => $transactionResponse->getAvsResultCode(),
                                'cvv_result_code' => $transactionResponse->getCvvResultCode(),
                                'cavv_result_code' => $transactionResponse->getCavvResultCode(),
                                'ref_trans_id' => $transactionResponse->getRefTransID(),
                                'transaction_status' => $transactionCode,
                                'ref_id' => $response->getRefId()

                            ];
                            if ($profile_ids) {
                                if (!$profile_ids['payment_profile_updated'])
                                    //send mail to admin
                                    unset($profile_ids['payment_profile_updated']);
                                $return['customer'] =   array_merge($return['customer'], $profile_ids);
                            }
                        endif;
                        //transaction response end
                    }
                } else {
                    if ($tresponse->getErrors() != null) {
                        $msg = $tresponse->getErrors()[0]->getErrorText();
                    }
                    $return = ['status' => 'error', 'message' => "Payment Failed : " . $msg];
                }
            } else {
                $msg = $response->getMessages()->getMessage()[0]->getText();
               // $msg .= $response->getTransactionResponse()->getErrors()[0]->getErrorText();
                $return = ['status' => 'error', 'message' => "Payment Failed : " . $msg];
            }
        } else {
            $return = ['status' => 'error', 'message' => "Something went wrong. Please try after some time"];
        }
        return $return;
    }

    public static function createCustomerProfileFromTransaction($transId = null, $user = null, $merchantAuthentication = false, $logConfig = false)
    {
        if (!$transId || !$user)
            return false;

        if ($logConfig == false) {
            $logConfig = CB_CONFIGS::configs();
        }

        if ($merchantAuthentication == false) {
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName($logConfig->authnet_login_id);
            $merchantAuthentication->setTransactionKey($logConfig->authnet_transaction_key);
        }

        // Set the transaction's refId
        $refId = 'ref' . time();
        $customerProfile = new AnetAPI\CustomerProfileBaseType();
        $customerProfile->setMerchantCustomerId($user->id);
        $customerProfile->setEmail($user->email);
        $customerProfile->setDescription(rand(0, 10000) . "sample description");

        $request = new AnetAPI\CreateCustomerProfileFromTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransId($transId);
        // You can either specify the customer information in form of customerProfileBaseType object
        $request->setCustomer($customerProfile);
        //  OR   
        // You can just provide the customer Profile ID
        //$request->setCustomerProfileId("123343");
        $controller = new AnetController\CreateCustomerProfileFromTransactionController($request);
        $response = $controller->executeWithApiResponse($logConfig->authnet_url);
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            //echo "SUCCESS: PROFILE ID : " . $response->getCustomerProfileId() . "\n";
            //echo $response->getCustomerPaymentProfileIdList()[0];
            $payment_profile_updated = self::updateCustomerPaymentProfile($response->getCustomerProfileId(), $response->getCustomerPaymentProfileIdList()[0], $user, $merchantAuthentication, $logConfig);

            $return = ['profile_id' => $response->getCustomerProfileId(), 'payment_profile_id' => $response->getCustomerPaymentProfileIdList()[0], 'payment_profile_updated' => $payment_profile_updated];
        } else {

            /*echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";*/
            $return = 0;
        }
        return $return;
    }

    public static function updateCustomerPaymentProfile($customerProfileId = null, $customerPaymentProfileId = null, $user = false, $merchantAuthentication, $logConfig)
    {

        $request = new AnetAPI\UpdateCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setCustomerProfileId($customerProfileId);
        $controller = new AnetController\GetCustomerProfileController($request);

        // Create the Customer Payment Profile object
        $paymentprofile = new AnetAPI\CustomerPaymentProfileExType();
        $paymentprofile->setCustomerPaymentProfileId($customerPaymentProfileId);


        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($user->firstName);
        $billto->setLastName($user->lastName);
        $paymentprofile->setBillTo($billto);

        // Submit a UpdatePaymentProfileRequest
        $request->setPaymentProfile($paymentprofile);
        $controller = new AnetController\UpdateCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse($logConfig->authnet_url);


        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            return true;
        } else {
            return false;
        }
    }
    
}
new PaymentHelper();
