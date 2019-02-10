<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ . '/php-outbrain-auth/OutbrainAuth.class.php';

class Rainbow_Outbrain_API {

    protected $CONSUMER_USER;
    protected $CONSUMER_PASS;
    protected $authHandle;
    private $ACCESS_TOKEN;
    private $ACCESS_TOKEN_OBJECT;

    const OUTBRAIN_API_ENDPOINT = "https://api.outbrain.com/amplify/v0.1";
    const REDIRECT_URI = 'oob';
    const SLEEP_DURATION = 30;
    const AUTH_HEADER_NAME = 'OB-TOKEN-V1';

    function __construct($username, $password, $access_token_object = null) {
        if (!isStringSet($username) || !isStringSet($password)) {
            throw new Exception('Credentails parameters missing.');
        }

        $this->CONSUMER_USER = $username;
        $this->CONSUMER_PASS = $password;
        $this->ACCESS_TOKEN_OBJECT = $access_token_object;
        //print_r($this);exit;
        $this->authHandle = new OutbrainAuth();
        try {
            if (is_null($this->ACCESS_TOKEN_OBJECT) || !is_array($this->ACCESS_TOKEN_OBJECT) || count($this->ACCESS_TOKEN_OBJECT) < 1) {
                $tokenResponse = $this->authHandle->get_access_token($this->CONSUMER_USER, $this->CONSUMER_PASS);
                $this->ACCESS_TOKEN_OBJECT['token_object'] = $tokenResponse;
                $this->ACCESS_TOKEN_OBJECT['token_timestamp'] = time();
                $this->ACCESS_TOKEN_OBJECT['expires_in'] = (60 * 60 * 24) * 29;
            } else {
                $current_time = time();
                $diff = $current_time - $this->ACCESS_TOKEN_OBJECT['token_timestamp'];
                if ($diff >= $this->ACCESS_TOKEN_OBJECT['expires_in']) {
                    $tokenResponse = $this->authHandle->get_access_token($this->CONSUMER_USER, $this->CONSUMER_PASS);
                    $this->ACCESS_TOKEN_OBJECT['token_object'] = $tokenResponse;
                    $this->ACCESS_TOKEN_OBJECT['token_timestamp'] = time();
                    $this->ACCESS_TOKEN_OBJECT['expires_in'] = (60 * 60 * 24) * 29;
                }
            }
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), $ex->getCode());
        }

        $this->ACCESS_TOKEN = $this->ACCESS_TOKEN_OBJECT['token_object'];
        if (!isStringSet($this->ACCESS_TOKEN)) {
            throw new Exception('Cannot access auth token.');
        }
    }

    function getTokenObject() {
        return $this->ACCESS_TOKEN_OBJECT;
    }

    protected function getAuthHeaders() {
        return array(
            self::AUTH_HEADER_NAME . ": " . safeReturnArray($this->ACCESS_TOKEN, safeReturnArray($this->ACCESS_TOKEN, '', 'token_data', self::AUTH_HEADER_NAME), self::AUTH_HEADER_NAME)
        );
    }

    function makeAPICall($url, $postdata = "", $auth = "") {
        my_var_dump($url, "url");
        $resp = $this->authHandle->fetch($url, $postdata, $auth, $this->getAuthHeaders());
        //var_dump($resp);
        my_var_dump($resp, "Response: $url");
        return $resp;
    }

    function makeUpateAPICall($url, $postdata = "", $auth = "") {
        my_var_dump($url, "url");
        $resp = $this->authHandle->updateCurl($url, $postdata, $auth, array_merge($this->getAuthHeaders(), array("Content-Type: application/json")));
        //var_dump($resp);
        my_var_dump($resp, "Response: $url");
        return $resp;
    }

    function makeAddAPICall($url, $postdata = "", $auth = array("Content-Type: application/json")) {
        my_var_dump($url, "url");
        $resp = $this->authHandle->addCurl($url, $postdata, $auth, array_merge($this->getAuthHeaders(), $auth));
        //var_dump($resp);
        my_var_dump($resp, "Response: $url");
        return $resp;
    }

    function getCampaign($advertiserID, $getdata = "", $postdata = "", $auth = "") {
        $url = self::OUTBRAIN_API_ENDPOINT . '/marketers/' . $advertiserID . '/campaigns';
        $url .= ((isset($getdata) && !empty($getdata)) ? "?{$getdata}" : "");
        return $this->makeAPICall($url, $postdata, $auth);
    }

    function getAds($campaignID, $getdata = "", $postdata = "", $auth = "") {
        $url = self::OUTBRAIN_API_ENDPOINT . '/campaigns/' . $campaignID . '/promotedLinks';
        $url .= ((isset($getdata) && !empty($getdata)) ? "?{$getdata}" : "");
        return $this->makeAPICall($url, $postdata, $auth);
    }

    function getAdvertiser($getdata = "", $postdata = "", $auth = "") {
        $url = self::OUTBRAIN_API_ENDPOINT . '/marketers';
        $url .= ((isset($getdata) && !empty($getdata)) ? "?{$getdata}" : "");
        return $this->makeAPICall($url, $postdata, $auth);
    }

    function getBudget($advertiserID, $getdata = "", $postdata = "", $auth = "") {
        $url = self::OUTBRAIN_API_ENDPOINT . '/marketers/' . $advertiserID . '/budgets';
        $url .= ((isset($getdata) && !empty($getdata)) ? "?{$getdata}" : "");
        return $this->makeAPICall($url, $postdata, $auth);
    }
    function getCountryData( $getdata = "", $postdata = "", $auth = "") {
        $url = self::OUTBRAIN_API_ENDPOINT . '/locations/search';
        $url .= ((isset($getdata) && !empty($getdata)) ? "?{$getdata}" : "");
        return $this->makeAPICall($url, $postdata, $auth);
    }

    function getReport_old($campaignID, $getdata = "", $postdata = "", $auth = "") {
        $url = self::OUTBRAIN_API_ENDPOINT . '/campaigns/' . $campaignID . '/performanceByPromotedLink';
        $url .= ((isset($getdata) && !empty($getdata)) ? "?{$getdata}" : "");
        return new cursorObject($this, $url, $postdata, $auth);
    }

    function getReport($campaignID, $advertiserID, $getdata = "", $postdata = "", $auth = "") {
        // https://api.outbrain.com/amplify/v0.1/reports/marketers/009725dbde8f043cdfcd647935a2559c4e/content?from=2017-03-29&to=2017-03-29&limit=100&offset=0&sort=-clicks&includeArchivedCampaigns=true&campaignId=00e7f3ce29d942ba43d8c7f652a3446ae6
        $url = self::OUTBRAIN_API_ENDPOINT . '/reports/marketers/' . $advertiserID . '/content';
        $url .= ((isset($getdata) && !empty($getdata)) ? "?{$getdata}" : "");
        $url .= "&campaignId=" . $campaignID;
        return new cursorObject($this, $url, $postdata, $auth);
    }

}

class cursorObject {

    private $baseObject;
    private $url;
    private $postData;
    private $auth;
    private $max_rows;
    private $current_index;
    private $currentResponseOtherData;

    const MAX_ROWS_PARAM = 'limit';
    const START_INDEX_PARAM = 'offset';
    const MAX_ROWS = 500;

    public function __construct($baseObject, $url = "", $postdata = "", $auth = "") {
        $this->baseObject = $baseObject;
        $this->url = $url;
        $this->postData = $postdata;
        $this->auth = $auth;
        $this->max_rows = self::MAX_ROWS;
        $this->current_index = 0;
    }

    function setMaxRows($max) {
        if ($max > 300) {
            $this->max_rows = 300;
        } else {
            $this->max_rows = $max;
        }
    }

    function setStartIndex($index) {
        $this->current_index = $index;
    }

    public function getNext() {
        if ($this->current_index == -1) {
            return false;
        }
        $paginationParams = self::MAX_ROWS_PARAM . "=" . $this->max_rows . '&' . self::START_INDEX_PARAM . '=' . $this->current_index;
        $newUrl = $this->url;
        if (!isStringSet($this->postData)) {
            if (isStringSet(parse_url($this->url, PHP_URL_QUERY))) {
                $newUrl = $this->url . '&' . $paginationParams;
            } else {
                $newUrl = $this->url . '?' . $paginationParams;
            }
        }

        $response = $this->baseObject->makeAPICall($newUrl, $this->postData, $this->auth);
//        my_var_dump($response, 'json');
        $response = preg_replace('/("[\w.]+"):([\d.]+)/', '\\1:"\\2"', $response);
        $decodeResponse = json_decode($response, true);

        if (count($decodeResponse['results']) == $this->max_rows) {
            $this->setStartIndex($this->current_index + $this->max_rows);
        } elseif (count($decodeResponse['results']) < $this->max_rows || count($decodeResponse['results']) > $this->max_rows || isStringSet($this->postData)) {
            $this->setStartIndex(-1);
        }

        //$this->setCurrentCursorOtherResponseData($decodeResponse['errors'], $decodeResponse['timestamp']);
        return $decodeResponse['results'];
    }

}
