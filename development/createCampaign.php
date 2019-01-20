<?php



if (count($arrLocalMasterCampaign[masterLocalCampaign::ColName_country]) > 0) {
    $country_targeting_value = array_map("strtoupper", $arrLocalMasterCampaign[masterLocalCampaign::ColName_country]);
    $country_targeting[self::ColName_API_type] = "INCLUDE";
    $country_targeting[self::ColName_API_value] = $country_targeting_value;
}

$arrLocalMasterCampaign[masterLocalCampaign::ColName_device] = explode("_", $arrLocalMasterCampaign[masterLocalCampaign::ColName_device]);

if (count($arrLocalMasterCampaign[masterLocalCampaign::ColName_device]) > 0) {
    foreach ($arrLocalMasterCampaign[masterLocalCampaign::ColName_device] as $device) {
        if ($device == 'desktop') {
            $platform_targeting_value[] = 'DESK';
        } else if ($device == 'mobile') {
            $platform_targeting_value[] = 'PHON';
        }
    }
    $platform_targeting[self::ColName_API_type] = "INCLUDE";
    $platform_targeting[self::ColName_API_value] = $platform_targeting_value;
}


//$start_date = str_replace('/', '-', $arrLocalMasterCampaign[masterLocalCampaign::ColName_start_date]);
//$end_date = str_replace('/', '-', $arrLocalMasterCampaign[masterLocalCampaign::ColName_end_date]);
$originalWords = array("{source}", "{campaign_name}", "{medium}", "{local_ad_id}", "{local_ad_id}");
$replaceWords = array("taboola", $arrLocalMasterCampaign[masterLocalCampaign::ColName_campaign_name], "cpc", $arrLocalMasterCampaign[masterLocalAds::ColName_Id], $arrLocalMasterCampaign[masterLocalAds::ColName_Id]);
$newTrackingCode = str_replace($originalWords, $replaceWords, $arrLocalMasterCampaign[masterLocalCampaign::ColName_tracking_code]);
$var = $arrLocalMasterCampaign[masterLocalCampaign::ColName_budgetType];

$arrayTaboolaTargetting = array(
    self::ColName_API_name => $arrLocalMasterCampaign[masterLocalCampaign::ColName_campaign_name],
    self::ColName_API_branding_text => $arrLocalMasterCampaign[masterLocalCampaign::ColName_campaign_name],
    self::ColName_API_start_date => date("Y-m-d", strtotime($arrLocalMasterCampaign[masterLocalCampaign::ColName_start_date])),
    self::ColName_API_end_date => safeReturnArray($arrLocalMasterCampaign, '', masterLocalCampaign::ColName_end_date) ? date("Y-m-d", strtotime(safeReturnArray($arrLocalMasterCampaign, '', masterLocalCampaign::ColName_end_date))) : "",
    self::ColName_API_cpc => $arrLocalMasterCampaign[masterLocalCampaign::ColName_bid_amount],
    self::ColName_API_spending_limit => $arrLocalMasterCampaign[masterLocalCampaign::ColName_bid_budget],
    self::ColName_API_spending_limit_model => ($var == "DAILY") ? 'MONTHLY' : 'ENTIRE',
    self::ColName_API_country_targeting => $country_targeting,
    self::ColName_API_platform_targeting => $platform_targeting,
    self::ColName_API_tracking_code => isset($newTrackingCode) ? $newTrackingCode : "utm_source=taboola&utm_medium=cpc&utm_campaign='" . str_replace(" ", "_", $arrLocalMasterCampaign[masterLocalCampaign::ColName_campaign_name]) . "'&utm_content='" . $arrLocalMasterCampaign[masterLocalAds::ColName_Id] . "'&ybid='" . $arrLocalMasterCampaign[masterLocalAds::ColName_Id],
    self::ColName_API_daily_cap => isset($arrLocalMasterCampaign[self::ColName_API_daily_cap]) ? $arrLocalMasterCampaign[self::ColName_API_daily_cap] :
            $arrLocalMasterCampaign[masterLocalCampaign::ColName_bid_budget],
    self::ColName_API_comments => isset($arrLocalMasterCampaign[self::ColName_API_comments]) ? $arrLocalMasterCampaign[self::ColName_API_comments] : '',
    self::ColName_API_is_active => true
);

try {
    $requestObj = json_encode($arrayTaboolaTargetting);
    $url = self::TABOOLA_API_ENDPOINT . '/' . $this->advertiserId . '/campaigns/';

    $response = $this->objRainbow_Taboola_API->makeAPICall($url, $requestObj);

    $responseObj = json_decode($response, true);
} catch (Exception $ex) {
    print_r($ex);
    throw new Exception($ex->getMessage(), $ex->getCode());
}
?>