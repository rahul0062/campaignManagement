<?php

const TABOOLA_API_ENDPOINT = 'https://backstage.taboola.com/backstage/api/1.0';

function makeAPICall(){
    // curl to get data
}
try {

    
    $country_targeting_value = array_map("strtoupper", 'US');
    $platform_targeting_value = array_map();
    $country_targeting['type'] = "INCLUDE";
    $country_targeting['value'] = $country_targeting_value;
    $platform_targeting_value[] = 'DESK';
    $platform_targeting['type'] = "INCLUDE";
    $platform_targeting['value'] = $platform_targeting_value;
    $advertiserId = '<advertiserId>';

    //$var = $arrLocalMasterCampaign['budgetType'];

    $arrayTaboolaTargetting = array(
        'name' => 'my_first_campaign',
        'branding_text' => 'my_first_branding_text',
        'start_date' => '<start_date>',
        'end_date' => '<end_date>',
        'cpc' => '10',
        'spending_limit' => '1000',
        'spending_limit_model' =>  'MONTHLY',
        'country_targeting' => $country_targeting,
        'platform_targeting' => $platform_targeting,
        'tracking_code' => '',
        'daily_cap' => '1000',
        'comments' => '',
        'is_active' => true
    );

    $requestObj = json_encode($arrayTaboolaTargetting);
    $url = self::TABOOLA_API_ENDPOINT . '/' . $advertiserId . '/campaigns/';
    $response = makeAPICall($url, $requestObj);
    $responseObj = json_decode($response, true);
    $liveCampaign_id = 'Campaign Id: '.$requestObj['id']."\n";


    $arrayTaboolaTargetting = array(
        'url' => 'https://www.google.com/'
    );

    
    $requestObj = json_encode($arrayTaboolaTargetting);
    $url = self::TABOOLA_API_ENDPOINT . '/' . $advertiserId . '/campaigns/' . $liveCampaign_id . '/items/';

    $response = makeAPICall($url, $requestObj);

    $responseObj = json_decode($response, true);
    
    $timer = 0;

    if (isset($responseObj)) {
        while (true) {
            //Get the Ads
            if ($timer < 100) {
                $ad = '';

                if ($ad['status'] != 'CRAWLING' && $ad['status'] != 'RUNNING') {
                    try {
                        $responseObj['description'] = $arrAd['description'];
                        $arrUp = array(
                            'description' => 'first desc',
                            'live_ad_id' => $responseObj['id']
                        );
                        $result = $this->updateAd($arrUp);
                        $resultObj = json_decode($result, true);
                    } catch (Exception $ex) {
                        throw new Exception($ex->getMessage(), $ex->getCode());
                    }
                }
                $timer += 10;
                sleep(10);
            } else {
                break;
            }
        }
    }
    return $resultObj;
    


} catch (Exception $ex) {
    print_r($ex);
    throw new Exception($ex->getMessage(), $ex->getCode());
}
?>