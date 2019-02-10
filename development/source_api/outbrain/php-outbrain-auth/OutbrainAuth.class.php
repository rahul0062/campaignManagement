<?php

class OutbrainAuth {

    const AUTHORIZATION_ENDPOINT = 'https://api.outbrain.com/amplify/v0.1/login';

    public function fetch($url, $postdata = "", $auth = "", $headers = "") {
        $response = '';
        try {
            $response = $this->fetch_actual($url, $postdata, $auth, $headers);
        } catch (Exception $ex) {
            if ($ex->getCode() == '34001') {
                try {
                    sleep(5);
                    $response = $this->fetch_actual($url, $postdata, $auth, $headers);
                } catch (Exception $ex2) {
                    throw new Exception($ex2->getMessage(), $ex2->getCode());
                }
            } else {
                throw new Exception($ex->getMessage(), $ex->getCode());
            }
        }
        return $response;
    }

    public function fetch_actual($url, $postdata = "", $auth = "", $headers = "") {
        my_var_dump(func_get_args(), "Into " . __FUNCTION__);
        //my_var_dump($headers, "Request : request HEADER");

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

        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);

        //my_var_dump($response, "Responce : Data");

        if (empty($response)) {
            my_var_dump(curl_error($curl), "Curl error ");
            my_var_dump($response, "Response");
            curl_close($curl); // close cURL handler                        
            throw new Exception('API No Response', '34001');
        } else {
            $info = curl_getinfo($curl);
            curl_close($curl); // close cURL handler
            if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                my_var_dump($info['http_code'], "Received error: ");
                my_var_dump($response, "Response");
                throw new Exception($response, '34002');
            }
        }
        return $response;
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

        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (isStringSet($file)) {
            $fp = fopen($file, "w");
            curl_setopt($curl, CURLOPT_FILE, $fp);
        }
        $response = curl_exec($curl);

        if (empty($response)) {
            my_var_dump(curl_error($curl), "Curl error ");
            my_var_dump($response, "Response");
            curl_close($curl); // close cURL handler                        
            throw new Exception('API No Response', '34001');
        } else {
            $info = curl_getinfo($curl);
            curl_close($curl); // close cURL handler
            if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                my_var_dump($info['http_code'], "Received error: ");
                my_var_dump($response, "Response");
                throw new Exception($response, '34002');
            }
        }

        return $response;
    }

    public function get_access_token($username, $password, $url = "") {

        if ($url == '') {
            $url = self::AUTHORIZATION_ENDPOINT;
        }
        $auth = $username . ":" . $password;
        $response = self::fetch($url, "", $auth);
        my_var_dump($response, 'getToken');
        // Convert the result from JSON format to a PHP array 
        $jsonResponse = json_decode($response, true);
        return $jsonResponse;
    }

    public function updateCurl($url, $postdata = "", $auth = "", $headers = "") {
        $response = '';
        try {
            $response = $this->update_actual($url, $postdata, $auth, $headers);
        } catch (Exception $ex) {
            // Retry 
            my_var_dump($ex->getMessage(), "\nAPI Error Recieved. %s");
            if ($ex->getCode() == '34001') {
                try {
                    sleep(5);
                    $response = $this->update_actual($url, $postdata, $auth, $headers);
                } catch (Exception $ex2) {
                    throw new Exception($ex2->getMessage(), $ex2->getCode());
                }
            } else {
                throw new Exception($ex->getMessage(), $ex->getCode());
            }
        }


        return $response;
    }

    public function update_actual($url, $postdata = "", $auth = "", $headers = "") {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");

        if ($postdata) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        } else {
            curl_setopt($curl, CURLOPT_POST, false);
        }
        if ($auth) {
            curl_setopt($curl, CURLOPT_USERPWD, $auth);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        $response = curl_exec($curl);
        if (empty($response)) {
            my_var_dump(curl_error($curl), "Curl error ");
            my_var_dump($response, "Response");
            curl_close($curl); // close cURL handler                        
            throw new Exception('API No Response', '34001');
        } else {
            $info = curl_getinfo($curl);
            curl_close($curl); // close cURL handler
            if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                my_var_dump($info['http_code'], "Received error: ");
                my_var_dump($response, "Response");
                throw new Exception($response, '34002');
            }
        }
        return $response;
    }

    public function addCurl($url, $postdata = "", $auth = "", $headers = "") {
        $response = '';
        try {
            $response = $this->add_actual($url, $postdata, $auth, $headers);
        } catch (Exception $ex) {
            my_var_dump($ex->getMessage(), "\nAPI Error Recieved. %s");
            if ($ex->getCode() == '34001') {
                try {
                    sleep(5);
                    $response = $this->add_actual($url, $postdata, $auth, $headers);
                } catch (Exception $ex2) {
                    throw new Exception($ex2->getMessage(), $ex2->getCode());
                }
            } else {
                throw new Exception($ex->getMessage(), $ex->getCode());
            }
        }
        return $response;
    }

    public function add_actual($url, $postdata = "", $auth = "", $headers = "") {

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);

        //curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

        if ($postdata) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        } else {
            curl_setopt($curl, CURLOPT_POST, false);
        }
        /* if ($auth){
          curl_setopt($curl, CURLOPT_USERPWD, $auth);
          } */

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        $response = curl_exec($curl);
        if (empty($response)) {
            my_var_dump(curl_error($curl), "Curl error ");
            my_var_dump($response, "Response");
            curl_close($curl); // close cURL handler                        
            throw new Exception('API No Response', '34001');
        } else {
            $info = curl_getinfo($curl);
            curl_close($curl); // close cURL handler
            if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                my_var_dump($info['http_code'], "Received error: ");
                my_var_dump($response, "Response");
                throw new Exception($response, '34002');
            }
        }
        return $response;
    }

}

?>
