<?php

   $url = 'https://api.outbrain.com/amplify/v0.1';


    $startDate = '<start_date>';
    $endDate = '<end_date>';
    $budgetType = "CAMPAIGN";
    $advertiserId = '<avertiser_id>';
    $username = 'oubrain@realunity.in';
    $password ='H@rdik@123';
    $authHandle = new  CurlClass();


    $loginurl = $url.'/login';
    echo $loginurl."\n";
    $auth = $username . ":" . $password; 
    echo  $auth."\n";
    
    try{
        $response = $authHandle->fetch($loginurl, "", $auth);
    }catch(Exception $ex){
        echo "<pre>"; print_r($ex);

    }
    

    $authHeader = array(
        'OB-TOKEN-V1'=>'<access_token>'
    );
    $country = '';

    $url = $url . "/locations/search?limit=300&geoType=Country&term=" . urlencode($country);
    try {
        $responseStr = $authHandle->fetch($url, "", "", $authHeader, "GET");
    } catch (Exception $ex) {
        echo '<pre>'; print_r($ex);
        
    }
    
    $arrBudget = array();
    $arrBudget['runForever'] = false;
    $arrBudget['endDate'] = $endDate;
    $arrBudget['name'] = 'my_first_campaign_budget';
    $arrBudget['amount'] ='5000';
    $arrBudget['type'] = $budgetType;
    $arrBudget['pacing'] = "AUTOMATIC";
    $arrBudget['startDate'] = $startDate;

    $url = $url . '/marketers/' . $advertiserId . '/budgets';
    $responseStr = $authHandle->fetch($url, json_encode($arrBudget), "", $authHeader, "POST");
    $responseArr = json_decode($responseStr, true);
    $budgetId = (string)$responseArr['id'];

    echo 'Budget Id: '.$budgetId."\n";

    //CAMPAIGN ARRAY
    $campaignObject = array(
        'name' => 'my_first_campaign',
        'cpc' => '10',
        'enabled' => "true",
        'targeting' => array('platform'=>array("DESKTOP","MOBILE"),'locations'=>array('<location_id>'))
    );

    $campaignObject['budgetId'] = $budgetId;

    $url = $url . '/campaigns';
    $responseStr = $authHandle->fetch($url, json_encode($campaignObject), "", $authHeader, "POST");
    $responseArr = json_decode($responseStr, true);
    $campaignId = (string) $responseArr['id'];

    $url = $this->url . '/campaigns/' . $campaignId . '/promotedLinks';
    $imageName = $adsObject[masterLocalAds::ColName_image_url];
    $imageFilePath = '';
    $imageFilePath = realpath($imageFilePath);
    $mimeType = mime_content_type($imageFilePath);

    $clickURL = 'https://www.google.com/'; 

    $arrayAds = array(
        'text' => 'my first ad',
        'url' => $clickURL,
        'enabled' => "true",
        'image' => $curlFileObject
    );

    $me = array_pop($authHeader);
    $responseStr = fetch($url, $arrayAds, "", $authHeader, "POST");
    array_push($authHeader, $me);

    $responseArr = json_decode($responseStr, true);
    $adId = (string)$responseArr['id'];
    echo 'Ad Id: '.$adId;
        


?>