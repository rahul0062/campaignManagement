<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ . '/php-taboola-oauth2/TaboolaOAuth2.class.php';
require_once __DIR__ . '/php-taboola-oauth2/cursorObject.php';

//use \taboola;

class Rainbow_Taboola_API {

    protected $CONSUMER_KEY;
    protected $CONSUMER_SECRET;
    protected $CONSUMER_USERNAME;
    protected $CONSUMER_PASSWORD;
    protected $authHandle;
    private $ACCESS_TOKEN;
    private $REFRESH_TOKEN_OBJECT;

    const TABOOLA_API_ENDPOINT = "https://backstage.taboola.com/backstage/api/1.0";
    const REDIRECT_URI = 'oob';
    const SLEEP_DURATION = 30;

    function __construct($consumerKey, $consumerSecret, $consumer_username, $consumer_password, $refresh_token = null) {
        if (!isStringSet($consumerKey) || !isStringSet($consumerSecret) || !isStringSet($consumer_username) || !isStringSet($consumer_password)) {
            throw new Exception('Credentails parameters missing.');
        }

        $this->CONSUMER_KEY = $consumerKey;
        $this->CONSUMER_SECRET = $consumerSecret;
        $this->CONSUMER_USERNAME = $consumer_username;
        $this->CONSUMER_PASSWORD = $consumer_password;

        $this->authHandle = new \taboola\TaboolaOAuth2();
        if (is_null($refresh_token) || !is_array($refresh_token) || count($refresh_token) < 1) {
            $tokenResponse = $this->authHandle->get_access_token($this->CONSUMER_KEY, $this->CONSUMER_SECRET, $this->CONSUMER_USERNAME, $this->CONSUMER_PASSWORD);
            $this->REFRESH_TOKEN_OBJECT = $tokenResponse;
            $this->REFRESH_TOKEN_OBJECT['token_timestamp'] = time();
        } else {
            $current_time = time();
            $diff = $current_time - $refresh_token['token_timestamp'];
            if ($diff >= $refresh_token['expires_in']) {
                $tokenResponse = $this->authHandle->get_access_token_refresh_token($this->CONSUMER_KEY, $this->CONSUMER_SECRET, $refresh_token['refresh_token']);
                $this->REFRESH_TOKEN_OBJECT = $tokenResponse;
                $this->REFRESH_TOKEN_OBJECT['token_timestamp'] = time();
            } else {
                $this->REFRESH_TOKEN_OBJECT = $refresh_token;
            }
        }
        $this->ACCESS_TOKEN = $this->REFRESH_TOKEN_OBJECT['access_token'];
        if (!isStringSet($this->ACCESS_TOKEN)) {
            throw new Exception('Cannot access auth token.');
        }
    }

    function getRefershTokenObject() {
        return $this->REFRESH_TOKEN_OBJECT;
    }

    protected function getAuthHeaders() {
        return array(
            'Authorization: Bearer ' . $this->ACCESS_TOKEN,
            'Accept: application/json',
            'Content-Type: application/json'
        );
    }

    function makeAPICall($url, $postdata = "", $auth = "") {
        //my_var_dump($url, "url");
        $resp = $this->authHandle->fetch($url, $postdata, $auth, $this->getAuthHeaders());
        
        return $resp;
    }

    function getCampaign($advertiserID, $getdata = "", $postdata = "", $auth = "") {
        $url = self::TABOOLA_API_ENDPOINT . '/' . $advertiserID . '/campaigns/';
        $url .= ((isset($getdata) && !empty($getdata)) ? "?{$getdata}" : "");
        $response = $this->makeAPICall($url);
        $responseObj = json_decode($response, true);
        return $responseObj;
    }

    function getAds($advertiserID, $campaignID, $getdata = "", $postdata = "", $auth = "") {
        $url = self::TABOOLA_API_ENDPOINT . '/' . $advertiserID . '/campaigns/' . $campaignID . '/items/';
        $url .= ((isset($getdata) && !empty($getdata)) ? "?{$getdata}" : "");
        $response = $this->makeAPICall($url);
        $responseObj = json_decode($response, true);
        return $responseObj;
    }

    
    function getReport($advertiserID, $campaignID, $getdata = "", $postdata = "", $auth = "") {
        $url = self::TABOOLA_API_ENDPOINT . '/' . $advertiserID . '/reports/top-campaign-content/dimensions/item_breakdown';
        $url .= ((isset($getdata) && !empty($getdata)) ? "?{$getdata}" : "");
        return new \taboola\cursorObject($this, $url, $postdata, $auth);
    }

    function getReportStatus($token, $advertiser, $return = false) {
        $url = self::TABOOLA_API_ENDPOINT . '/reports/' . $token . '/?advertiserId=' . $advertiser;
        $response = $this->makeAPICall($url);
        $responseObj = json_decode($response, true);
        return $responseObj;
    }

    function waitForReportReady($token, $advertiser, $return = false) {
        $url = self::TABOOLA_API_ENDPOINT . '/reports/' . $token . '/?advertiserId=' . $advertiser;
        $responseObj = $this->getReportStatus($token, $advertiser, $return);
        var_dump($responseObj);
        while ($responseObj['response']['status'] == 'running' && !$return) {
            sleep(self::SLEEP_DURATION);
            $responseObj = $this->getReportStatus($token, $advertiser, $return);
        }
        $reportJobStatus = $responseObj['response']['status'];
        if ($return) {
            if ($reportJobStatus === 'completed') {
                return $responseObj;
            }
            $error = $reportJobStatus;
            throw new Exception($error);
        }

        return $responseObj;
    }

}


