<?php

require_once __DIR__ . '/obBaseClass.php';
require_once __DIR__ . "/curlClass.class.php";

class OutbrainGeoLocation extends obBaseClass
{

    const outbrainConsumerName = "CONSUMER_USERNAME";
    
    protected $authHeader;
    protected $advertiserId;
    protected $apiHandle;
    
    
    function __construct($advertiser, $headers) {

        $this->url = parent::OUTBRAIN_API_ENDPOINT;
        $this->advertiserId = $advertiser;
        $this->apiHandle = new CurlClass();
        $this->authHeader = $headers;
    }

    
    private function getOutbrainGeoLocation($country = '')
    {
        my_var_dump(func_get_args(), "In " . __FUNCTION__);
        $url = $this->url . "/locations/search?limit=300&geoType=Country&term=" . urlencode($country);
        try {
            $responseStr = $this->apiHandle->fetch($url, "", "", $this->authHeader, "GET");
        } catch (Exception $ex) {
            my_var_dump($ex, 'Exception: ' . __FUNCTION__);
            $message = ($ex->getCode() == '2102') ? $this->apiHandle - getErrorResponse() : $ex->getMessage();
            $message = json_decode($message, true);
            throw new Exception($message['message'], $ex->getCode());
        }
        return $responseStr;
    }

    private function importOutbrainGeoLocation(array $country)
    {
        my_var_dump(func_get_args(), "In " . __FUNCTION__);
        try {
            $apiResponse = $this->getOutbrainGeoLocation($country['name']);
            $apiData = json_decode($apiResponse, true);
            foreach ($apiData as $obj) {
                if ($obj['name'] == $country['name'] && $obj['geoType']=='Country') {
                    $criteria = array(
                        'id' => $obj['id']
                    );
                    $obj['code'] = $country['code'];
                    
                }
            }
        } catch (Exception $ex) {
            my_var_dump($ex, 'Exception: ' . __FUNCTION__);
            throw new Exception($ex->getMessage(), $ex->getCode());
        }
    }

    

    public function getTargetingObject($arrLocalCampaign)
    {
        my_var_dump(func_get_args(), "In " . __FUNCTION__);

        $arrayGeoLocationId = array();
        $arrCountry = explode("_", $arrLocalCampaign[masterLocalCampaign::ColName_country]);

        foreach ($arrCountry as $keyGeo => $valGeo) {
            $country = $this->getGeoLocationData($valGeo);
            if (is_array($country) && count($country) > 0) {
                array_push($arrayGeoLocationId, $country['id']);
            } else {
                throw new Exception("CountryID not found for : {$valGeo}", "7101");
            }
        }

        //PREPARE DEVICE TARGETING
        $arrTmpDevice = explode("_", strtoupper($arrLocalCampaign[masterLocalCampaign::ColName_device]));


        $arraytargetDevice = array();
        foreach ($arrTmpDevice as $tmpDevice) {
            array_push($arraytargetDevice, strtoupper($tmpDevice));
        }

        //TARGETTING ARRAY
        $arrayTargeting = array(
            Campaign::ColName_Campaign_platform => $arraytargetDevice,
            Campaign::ColName_Campaign_locations => $arrayGeoLocationId
        );

        return $arrayTargeting;
    }

}
