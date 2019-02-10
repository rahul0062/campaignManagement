
const adsSdk = require('facebook-nodejs-business-sdk');
const AdAccount = adsSdk.AdAccount;
const Campaign = adsSdk.Campaign;

const access_token = 'EAADwX3AjbyABAHlqEQC7rZAzXY9je97W1BwMhHbUoLJJsN3ZANZBrByInh3rt4idWRoafsFlPePZAXaRWIFjgwE2CKbbniEq0sPEeYmZBM3wXdfFuDkqJLhdK23fPxnwnKSEAADwX3AjbyABAKRrZCxMZBg9P5r3aDNxHU8irKbaJdGUMwYyRK13YlDhQSk2FxRcbZBww6nMFsFCrwmlWXUiRD1FSu9TMK1yrVViKtvTKLYbt8GZANaU8YMPq5uuVAdZAaByyo6CmsiQkS6T5kXjEVudY6TsOWX9VZAsyOhdF6RU3XOZAGnBKvHZCPNwTo7uVMqSOZC03GgqINgZDZDbmi1rwvJDBn45re8ifHiR05d3SCsSrO9OV2EV7QiZAMtamPvwtvWj7AwGUqrACo3px5IOdZB3QZDZD';
const app_secret = '21c197427cfe15eeeb7a4b2622be9c51';
const app_id = '264292693929760';
const ad_account_id = 'act_309855264';
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
campaigns = (new AdAccount(id)).createCampaign(
  fields,
  params
);
campaigns
.then((result) => {
    logApiCallResult('campaign api call complete.', result);
    campaign_id = result.id;
    const fields = [
    ];
    
    let targeting = new targeting();
    targeting.setData({
        "geo_locations": {"countries":["US","GB"]}, 
        "publisher_platforms": ["instagram"], 
        "user_os": ["iOS"] 
    });
    return (new AdAccount(ad_account_id)).getReachEstimate([],{
        'targeting_spec':targeting,
        'optimize_for' : 'IMPRESSION',
    });
  })
  .then((result) => {
    logApiCallResult('targeting api call complete.', result);
    targeting = result;
    const fields = [
    ];
    const params = {
      'name':'Instagram Adset',
      'optimization_goal' : 'OFFSITE_CONVERSIONS',
      'billing_event' : 'IMPRESSIONS',
      'bid_amount' : '20',
      'daily_budget' : '1000',
      'campaign_id' : campaign_id,
      'targeting' :targeting,

    };
    return (new AdAccount(ad_account_id)).createAdSet(
      fields,
      params
    );
  })
  .then((result) => {
    logApiCallResult('ad_set api call complete.', result);
    ad_set_id = result.id;
    const fields = [
    ];

    creative = new AdCreative();
    creative.setData({
      'object_story_spec' : (new AdCreativeObjectiveStorySpec()).setData({
          'page_id' : page_id , 
          'instragram_actor_id' :instragram_actor_id,
          'link_data':(new AdCreativeLinkData()).setData({
            'image_hash':image_hash,
            'message':'Ad message',
            'caption':'www.example.com',
            'link':'<url>',
            'call_to_action':{
                'type':'learn_more',
                'value':{
                    'link':'<url>'
                }
            }
          })
    })
    });
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
