<?php

$url = "https://api.outbrain.com/amplify/v0.1";

function fetch($url, $postdata = '', $auth = '', $headers = '', $method = '', $file = '') {

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

    if (isStringSet($file)) {
        $fp = fopen($file, "wb+");
        curl_setopt($curl, CURLOPT_FILE, $fp);
    }
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
}

try {
    $startDate = '<start_date>';
    $endDate = '<end_date>';
    $budgetType = "CAMPAIGN";
    $advertiserId = '<avertiser_id>';
    $authHeader = array(
        'OB-TOKEN-V1'=>'<access_token>'
    );

    $arrBudget = array();
    $arrBudget[Budget::ColName_runForever] = false;
    $arrBudget[Budget::ColName_Budget_endDate] = $endDate;
    $arrBudget[Budget::ColName_Budget_name] = 'my_first_campaign_budget';
    $arrBudget[Budget::ColName_Budget_amount] ='5000';
    $arrBudget[Budget::ColName_Budget_type] = $budgetType;
    $arrBudget[Budget::ColName_Budget_pacing] = "AUTOMATIC";
    $arrBudget[Budget::ColName_Budget_startDate] = $startDate;

    $url = $url . '/marketers/' . $advertiserId . '/budgets';
    $responseStr = fetch($url, json_encode($arrBudget), "", $authHeader, "POST");
    $responseArr = json_decode($responseStr, true);
    $budgetId = (string)$responseArr['id'];

    echo 'Budget Id: '.$budgetId."\n";

    //CAMPAIGN ARRAY
    $campaignObject = array(
        Campaign::ColName_Campaign_name => 'my_first_campaign',
        Campaign::ColName_Campaign_cpc => '10',
        Campaign::ColName_Campaign_enabled => "true",
        Campaign::ColName_Campaign_targeting => array('platform'=>array("DESKTOP","MOBILE"),'locations'=>array('<location_id>'))
    );

    $campaignObject['budgetId'] = $budgetId;

    $url = $url . '/campaigns';
    $responseStr = fetch($url, json_encode($campaignObject), "", $authHeader, "POST");
    $responseArr = json_decode($responseStr, true);
    $campaignId = (string) $responseArr['id'];

    $url = $this->url . '/campaigns/' . $campaignId . '/promotedLinks';
    $imageName = $adsObject[masterLocalAds::ColName_image_url];
    $imageFilePath = '';
    $imageFilePath = realpath($imageFilePath);
    $mimeType = mime_content_type($imageFilePath);

    $clickURL = 'https://www.google.com/'; 

    $arrayAds = array(
        self::ColName_PromotedLinks_text => 'my first ad',
        self::ColName_PromotedLinks_url => $clickURL,
        self::ColName_PromotedLinks_enabled => "true",
        'image' => $curlFileObject
    );

    $me = array_pop($authHeader);
    $responseStr = fetch($url, $arrayAds, "", $authHeader, "POST");
    array_push($authHeader, $me);

    $responseArr = json_decode($responseStr, true);
    $adId = (string)$responseArr['id'];
    echo 'Ad Id: '.$adId;
        
} catch (Exception $ex) {
    my_var_dump($ex, 'Exception: ' . __FUNCTION__);
    $message = ($ex->getCode() == '2102') ? $this->apiHandle->getErrorResponse() : $ex->getMessage();
    $message = json_decode($message, true);
    throw new Exception($message['message'], $ex->getCode());
}

?>