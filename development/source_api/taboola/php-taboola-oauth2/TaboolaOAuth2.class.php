<?php

namespace taboola;
//namespace taboola\Exception;

class TaboolaOAuth2 {

    const AUTHORIZATION_ENDPOINT = 'https://backstage.taboola.com/backstage/oauth/token';
    const TOKEN_ENDPOINT = 'https://backstage.taboola.com/backstage/oauth/token';

    public function fetch($url, $postdata = "", $auth = "", $headers = "") {
        $response = array();
        $retryFlag = false;
        $retryCounter = 0;
        do {
            try {
                $response = $this->fetch_actual($url, $postdata, $auth, $headers);
                $retryFlag = false;
            } catch (Exception $ex) {
                // Retry 
                printf("\n\n===============================================\n");
                printf("\nAPI Error Recieved. %s", $ex->getMessage());
                printf("\nAPI Retry. %d", $retryCounter);
                printf("\n\n===============================================\n");
                $retryFlag = true;
            }
        } while ($retryFlag && $retryCounter++ < 3);
        return $response;
    }

    public function fetch_actual($url, $postdata = "", $auth = "", $headers = "") {
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
        curl_setopt($curl, CURLOPT_ENCODING, '');
        $response = curl_exec($curl);

        printf("URL: %s\nPost: %s\nAuth: %s\nHeaders: %s\n\n", $url, $postdata, $auth, serialize($headers));
        if (empty($response)) {
            // some kind of an error happened
            //die(curl_error($curl));
            echo "Curl error: " . curl_error($curl) . "\n";
            echo "Raw response:" . $response . "\n===============================\n";
            curl_close($curl); // close cURL handler          
            sleep(5);
            throw new \Exception('APIERROR');
        } else {
            $info = curl_getinfo($curl);
            curl_close($curl); // close cURL handler
            if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                echo "Received error: " . $info['http_code'] . "\n";
                echo "Raw response:" . $response . "\n===============================\n";
                //die();
                sleep(5);
                throw new \Exception('APIERROR');
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
            // some kind of an error happened
            die(curl_error($curl));
            curl_close($curl); // close cURL handler
        } else {
            $info = curl_getinfo($curl);
            curl_close($curl); // close cURL handler
            if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                echo "Received error: " . $info['http_code'] . "\n";
                echo "Raw response:" . $response . "\n";
                die();
            }
        }
        return $response;
    }

    public function getAuthorizationURL($client_id, $redirect_uri) {
        $url = self::AUTHORIZATION_ENDPOINT;
        $authorization_url = $url . '?' . 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&response_type=code';
        return $authorization_url;
    }

    public function get_access_token($clientId, $clientSecret, $username, $password) {
        $url = self::TOKEN_ENDPOINT;
        $postdata = array("client_id" => $clientId, "client_secret" => $clientSecret, "username" => $username, "password" => $password, "grant_type" => "password");

        //$auth = $clientId . ":" . $clientSecret;
        $response = self::fetch($url, http_build_query($postdata));
        my_var_dump($response, 'getToken');
        // Convert the result from JSON format to a PHP array 
        $jsonResponse = json_decode($response, true);
        my_var_dump($jsonResponse, 'getToken');
        return $jsonResponse;
    }

    public function get_access_token_refresh_token($clientId, $clientSecret, $refresh_token) {
        $url = self::TOKEN_ENDPOINT;
        $postdata = array("client_id" => $clientId, "client_secret" => $clientSecret, "refresh_token" => $refresh_token, "grant_type" => "refresh_token");
        //$auth = $clientId . ":" . $clientSecret;
        $response = self::fetch($url, http_build_query($postdata));
        //my_var_dump($response, 'Refresh_Token');
        // Convert the result from JSON format to a PHP array 
        $jsonResponse = json_decode($response, true);
        return $jsonResponse;
    }

}

?>
