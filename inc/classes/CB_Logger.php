<?php

class CB_Logger
{

    function __construct($body = 'Payload', $event = 'report', $status = 'success', $exception = null, $user_id = 12, $ip = null)
    {
        global $wpdb;

        $log = $wpdb->insert(
            tblAppLog,
            array(
                "event" => $event,
                "status" => $status,
                "exception" => $exception,
                "user_id" => $user_id,
                "created" => date('Y-m-d h:i:s'),
                "logged_by" => "User",
                "IP" => $ip ? $ip : $_SERVER['REMOTE_ADDR']
            )
        );

        if($log){
            return true;
        }else{
            return false;
        }

    }
}
