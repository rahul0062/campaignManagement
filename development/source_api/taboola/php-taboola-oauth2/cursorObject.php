<?php

namespace taboola;

class cursorObject {

    private $baseObject;
    private $url;
    private $postData;
    private $auth;
    private $max_rows;
    private $current_index;
    private $currentResponseOtherData;

    const MAX_ROWS_PARAM = 'mr';
    const START_INDEX_PARAM = 'si';
    const MAX_ROWS = 50;

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
        my_var_dump($response, 'json');
        $response = preg_replace('/("[\w.]+"):([\d.]+)/', '\\1:"\\2"', $response);
        $decodeResponse = json_decode($response, true);


        if (count($decodeResponse['results']) < $this->max_rows || count($decodeResponse['results']) > $this->max_rows || isStringSet($this->postData)) {
            $this->setStartIndex(-1);
        } else {
            $this->setStartIndex($this->current_index + $this->max_rows);
        }

        //$this->setCurrentCursorOtherResponseData($decodeResponse['http_status'], $decodeResponse['message']);
        return $decodeResponse['results'];
    }

    private function setCurrentCursorOtherResponseData($status = null, $message = null) {
        $this->currentResponseOtherData = array(
            'errors' => $status,
            'message' => $message
        );
    }

    function getCurrentResponseOtherData() {
        return $this->currentResponseOtherData;
    }

}
