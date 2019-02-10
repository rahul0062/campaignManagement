<?php

require "TaboolaOAuth2.class.php"; #Download here: https://github.com/saurabhsahni/php-yahoo-oauth2/

define("CONSUMER_KEY","4d3d84dd3d674a088ea5624984667980");
define("CONSUMER_SECRET","1521ff31996249498347bf69165dfc65");
define("CONSUMER_USERNAME","Akalra@forbes.com");
define("CONSUMER_PASSWORD","Taboola1");

$oauth2client=new TaboolaOAuth2();
$token=$oauth2client->get_access_token(CONSUMER_KEY,CONSUMER_SECRET,CONSUMER_USERNAME,CONSUMER_PASSWORD);
print_r($token);

$refToken = $oauth2client->get_access_token_refresh_token(CONSUMER_KEY,CONSUMER_SECRET,$token['refresh_token']);
print_r($refToken);
exit;

if (isset($_GET['code'])){
	$code=$_GET['code'];	
} 
else {
	$code=0;
}

//$code = 'x5457pu';

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

