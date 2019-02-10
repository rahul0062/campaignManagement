<?php

/*
  Example class to access Yahoo OAuth2 protected APIs, based on https://developer.yahoo.com/oauth2/guide/
  Find documentation and support on Yahoo Developer Network: https://developer.yahoo.com/forums

 */

class CurlClass {

    public function fetch($url, $postdata = '', $auth = '', $headers = '', $method = '', $file = '') {
        

        $curl = curl_init($url);
        if ($method == 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        } else if ($method == 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        } elseif ($postdata) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        }

        if ($auth) {
            curl_setopt($curl, CURLOPT_USERPWD, $auth);
        }

        if ($headers) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // if (isStringSet($file)) {
        //     $fp = fopen($file, "wb+");
        //     curl_setopt($curl, CURLOPT_FILE, $fp);
        // }

        $response = curl_exec($curl);

        $this->errorResponse = '';
        if (empty($response)) {
            $info = curl_getinfo($curl);
            $this->errorResponse = $info['http_code'];

            
            curl_close($curl);

            throw new Exception("Empty Response", "2101");
        } else {
            $info = curl_getinfo($curl);
            curl_close($curl);

            if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                
                $this->errorResponse = $response;

                $response = @json_decode($response, true);

                if(@$response && is_array(@$response['errors'])) {
                    $message = @$response['errors'][0]['message'] . ": " . @$response['errors'][0]['description'];
                }else{
                    $message = @$response['message'];
                }

                throw new Exception("Invalid Response: {$message}", "2102");
            }
        }

        return $response;
    }

    public function getErrorResponse() {
        return $this->errorResponse;
    }

    public function fetch_file($url, $postdata = "", $auth = "", $headers = "", $file = "") {
        $curl = curl_init($url);
        if ($postdata) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        } else {
            curl_setopt($curl, CURLOPT_POST, false);
        }
        if ($auth) {
            curl_setopt($curl, CURLOPT_USERPWD, $auth);
        }
        if ($headers) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if (isStringSet($file)) {
            $fp = fopen($file, "wb+");
            curl_setopt($curl, CURLOPT_FILE, $fp);
        }

        $response = curl_exec($curl);
        my_var_dump($response, 'Curl Response');

        if (empty($response)) {
            // some kind of an error happened
            die(curl_error($curl));
            curl_close($curl); // close cURL handler
        } else {
            $info = curl_getinfo($curl);
            curl_close($curl); // close cURL handler
            if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                echo "Received error: " . $info['http_code'] . "\n";
                echo "Raw response:" . $response . "\n";
//                die();
            }
        }
        return $response;
    }

}

?>