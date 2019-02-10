
const adsSdk = require('facebook-nodejs-business-sdk');
const AdAccount = adsSdk.AdAccount;
const Campaign = adsSdk.Campaign;

const access_token = '<access_token>';
const app_secret = '<app_secret>';
const app_id = '<app_id>';
const ad_account_id = '<user_id>';
const page_id ='<page_id>';
const instagram_actor_id = '<instagram_actor_id>';
const image_hash = '<image_hash_id>';
const api = adsSdk.FacebookAdsApi.init(access_token);
const showDebugingInfo = true; // Setting this to true shows more debugging info.
if (showDebugingInfo) {
  api.setDebug(true);
}

let campaigns;
let campaign_id;
let ad_set_id;
let creative;
let creative_id;
let targeting;


const logApiCallResult = (apiCallName, data) => {
  console.log(apiCallName);
  if (showDebugingInfo) {
    console.log('Data:' + JSON.stringify(data));
  }
};

const fields = [
];

params = {
  'name' : 'My campaign',
  'objective' : 'LINK_CLICKS',
  'status' : 'PAUSED',
};
campaigns = (new AdAccount(ad_account_id)).createCampaign(
  fields,
  params
);
campaigns
.then((result) => {
    logApiCallResult('campaign api call complete.', result);
    campaign_id = result.id;
    const fields = [
    ];
    const params = {
      'name' : 'My First Adset',
      'daily_budget' : '2000000',
      'start_time' : '<start_time>',
      'end_time' : '<end_time>',
      'campaign_id' : campaign_id,
      'bid_amount' : '100',
      'billing_event' : 'IMPRESSIONS',
      'user_os':['ios'],
      'optimization_for' : 'IMPRESSIONS',
      'targeting' : {'geo_locations':{'countries':['US']},'publisher_platforms':['instagram']},
      'status' : 'PAUSED',
    };
    return adsets = (new AdAccount(ad_account_id)).createAdSet(
      fields,
      params
    );
  })
  
  .then((result) => {
    logApiCallResult('ad_set api call complete.', result);
    ad_set_id = result.id;
    const fields = [
    ];
    
    const params = {
      'image_hash' : image_hash,
      'object_story_spec' : {'page_id':page_id,'instagram_actor_id':instagram_actor_id,'link_data':{'image_hash':image_hash,'link':'www.google.com','name':'Creative message','call_to_action':{'type':'LEARN_MORE'}}},
    };
    
    return (new AdAccount(ad_account_id)).createAdCreative(
      fields,
      params
    );
  })
  .then((result) => {
    logApiCallResult('creative api call complete.', result);
    creative_id = result.id;
    const fields = [
    ];
    const params = {
      'status' : 'PAUSED',
      'adset_id' : ad_set_id,
      'name' : 'My Ad',
      'creative' : {'creative_id':creative_id},
    };
    return (new AdAccount(ad_account_id)).createAd(
      fields,
      params
    );
  })
  .catch((error) => {
    console.log(error);
  });
