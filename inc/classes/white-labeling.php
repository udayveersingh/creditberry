<?php

class WhiteLabeling
{
    function __construct()
    {
        add_action('template_redirect', array($this, 'cb_check_funnel_user_exists'));
        add_filter('body_class', array($this, 'cb_modify_body_class'));
        add_filter('document_title_parts', array($this, 'cb_modify_page_title'));
    }

    function cb_get_all_funnel_users()
    {
        $postData = http_build_query(array(
            'domain' =>  "creditberry.com",
        ));
        $configs = CB_CONFIGS::configs();
        $url = $configs->mcd_url . "/api/wpapi/getFunnelUsers";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function cb_get_single_funnel_users($username)
    {
        $postData = http_build_query(array(
            'username' =>  $username,
        ));
        $configs = CB_CONFIGS::configs();
        $url = $configs->mcd_url . "/api/wpapi/getSingleFunnelUser";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function cb_get_funnel_pricing($funnel_id)
    {
        $postData = http_build_query(array(
            'funnel_id' =>  $funnel_id,
        ));
        $configs = CB_CONFIGS::configs();
        $url = $configs->mcd_url . "/api/wpapi/getPricingByFunnelId";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function cb_check_funnel_user_exists()
    {
        global $isFunnel,$isFunnelLog;
        $configs = CB_CONFIGS::configs();
        $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri_segments = explode('/', $uri_path);
        if (isset($uri_segments[1]) && $uri_segments[1] != "") {
            $segment = trim($uri_segments[1]);
            $users = $this->cb_get_all_funnel_users();
            $users = json_decode($users);
            if (is_array($users->Data) && in_array($segment, $users->Data)) {
                $isFunnel = true;
                $userData = $this->cb_get_single_funnel_users($segment);
                $userData = json_decode($userData);
                $data = new \stdClass;
                if ($userData) {
                    $userData = $userData->Data;
                    if($userData->logo){
                        $isFunnelLog = $configs->mcd_url."/assets/logos/".$userData->logo;
                    }
                    $pricing = WhiteLabeling::cb_get_funnel_pricing($userData->id);
                    if ($pricing) {
                        $pricing = json_decode($pricing);
                        $pricing = $pricing->Data;
                    } else {
                        $pricing = array();
                    }
                } else {
                    $userData = new \stdClass;
                }
                include(get_template_directory() . '/page-template/white-labeling.php');
                exit();
            }
        } else {
            $isFunnel = false;
        }
    }

    function cb_modify_body_class($classes)
    {
        global $isFunnel;
        if ($isFunnel) {
            if (in_array('error404', $classes)) {
                unset($classes[array_search('error404', $classes)]);
                $classes[] = "page-funnel-user";
            }
        }
        return $classes;
    }

    function cb_modify_page_title($title_parts)
    {
        global $isFunnel;

        if ($isFunnel) {
            $title_parts['title'] = 'Registration';
        }
        return $title_parts;
    }
}
new WhiteLabeling();
