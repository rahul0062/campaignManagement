
const adsSdk = require('facebook-nodejs-business-sdk');
const AdAccount = adsSdk.AdAccount;
const Campaign = adsSdk.Campaign;

const access_token = 'EAADwX3AjbyABAH1KwD1O2uoWo6XBR9sexGvMsUBHfIZA7tsEKJusiZBNNax9eAeRgNZB83dU8a3Wfz8OIvHoBCgFKvpIiZB6x84ocJs53FgQab2LaB9My15ZCaUtUeREOsI9RawblFSwa0YG9qtSJTMA7BmgPh5r5hIVyRPf63ZBZAw7tNZAZAIXTQeVFKVoB4AE1fgF0tCda7gZDZD';
const app_secret = '21c197427cfe15eeeb7a4b2622be9c51';
const app_id = '264292693929760';
const ad_account_id = 'act_309855264';
const page_id ='2178358439160316';
const image_hash = ' fd54ae4d1489e3cff4a5cc6bb48cc19b';
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
  'name' : 'First instagram campaign',
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
      'start_time' : '2019-02-02T21:37:55-0800',
      'end_time' : '2019-02-12T21:37:55-0800',
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
      'object_story_spec' : {'page_id':page_id,'instagram_actor_id':'677892648994930','link_data':{'image_hash':image_hash,'link':'www.google.com','name':'Creative message','call_to_action':{'type':'LEARN_MORE'}}},
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
