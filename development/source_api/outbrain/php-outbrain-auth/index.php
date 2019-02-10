<?php
require __DIR__ . "/OutbrainAuth.class.php"; 
require_once __DIR__ . "/../../supportCode/config/config.php";
require_once FUNCTIONS_DIR . "staticFunctions.php";


define("CONSUMER_USERNAME","Achir");
define("CONSUMER_PASSWORD","Forbes1917");

$gemini_api_endpoint="https://api.outbrain.com/amplify/v0.1/";

$authclient=new OutbrainAuth();
$token=$authclient->get_access_token(CONSUMER_USERNAME,CONSUMER_PASSWORD);

var_dump($token);
exit;


if($code){
	 #oAuth 3-legged authorization is successful, fetch access token 	
	 $token=$oauth2client->get_access_token(CONSUMER_KEY,CONSUMER_SECRET,$redirect_uri,$code);

	 #access token is available. Do API calls.	 

	 $headers= array(
					'Authorization: Bearer '.$token,
					'Accept: application/json',
					'Content-Type: application/json'
					);

	 //Fetch Advertiser Name and Advertiser ID
	 $url=$gemini_api_endpoint."/advertiser/";

	 $resp=$oauth2client->fetch($url,$postdata="",$auth="",$headers);
	 $jsonResponse = json_decode( $resp);
	 $advertiserName = $jsonResponse->response[0]->advertiserName; 
	 $advertiserId = $jsonResponse->response[0]->id; 
	 echo "Welcome ".$advertiserName;
         exit;
	 //Create a new campaign
	 $url=$gemini_api_endpoint."/campaign";
	 $postdata='{
	  "status":"PAUSED",
	  "campaignName":"NativeAdsCampaign",
	  "budget": 3000,
	  "budgetType": "LIFETIME",
	  "advertiserId": '.$advertiserId.',
	  "channel":"NATIVE"
	  }';

	 $resp=$oauth2client->fetch($url,$postdata=$postdata,$auth="",$headers);
	 $jsonResponse = json_decode( $resp);

	 $campaignID=$jsonResponse->response->id;
	 $campaignName=$jsonResponse->response->campaignName;

	 echo "\n<br>Created a new campaign with ID: ".$campaignID;

	 //Read specific campaign data
	 $url=$gemini_api_endpoint."/campaign/".$campaignID;
	 $resp=$oauth2client->fetch($url,$postdata="",$auth="",$headers);
	 $jsonResponse = json_decode( $resp);
	 echo "\n<br> Campaign object:<br>\n";
	 print_r($jsonResponse->response);
}
else {
    /* no valid access token available, go to authorization server */
    header("HTTP/1.1 302 Found");
    header("Location: " . $oauth2client->getAuthorizationURL(CONSUMER_KEY,$redirect_uri));
    exit;
}

?>

