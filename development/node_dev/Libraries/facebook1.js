/**
 * Copyright (c) 2017-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 * @flow
 */

const adsSdk = require('facebook-nodejs-business-sdk')
const Business = adsSdk.Business;
const ProductCatalog = adsSdk.ProductCatalog;
const ProductFeed = adsSdk.ProductFeed;
const ProductSet = adsSdk.ProductSet;
const ExternalEventSource = adsSdk.ExternalEventSource;
const AdAccount = adsSdk.AdAccount;
const Campaign = adsSdk.Campaign;
const AdSet = adsSdk.AdSet;
const AdCreative = adsSdk.AdCreative;
const Ad = adsSdk.Ad;
const AdPreview = adsSdk.AdPreview;

let access_token = 'EAAmRRyZA0UH8BACIVMs90j1N7V3ZCiiFDXXIerC0OT9JpRHSrF0seHRy6TyDDrehB3A6tnXhvBGmJAsN9AB4V4wJjzDaZA6tPS40D6rj0NOkPI97TsuiDHsWxGZByWuWwM2tPUMVhdp4Saynq5Lx253h5fE4haQqApchUQyEOuISleSjM0by';
let app_secret = '06b8a1dbb15bae163940dbf78f6dd239';
let ad_account_id = '496422187546884';
let business_id = '334491630684214';
let page_id = '293985261303789';
let pixel_id = '279264656072819';
let app_id = '2693009564258431';
const api = adsSdk.FacebookAdsApi.init(access_token);
const account = new AdAccount(ad_account_id);
const showDebugingInfo = true; // Setting this to true shows more debugging info.
if (showDebugingInfo) {
  api.setDebug(true);
}

let product_catalog;
let product_catalog_id;
let product_feed;
let product_feed_id;
let product_set;
let product_set_id;
let external_event_source;
let external_event_source_id;
let campaign;
let campaign_id;
let ad_set;
let ad_set_id;
let creative;
let creative_id;
let ad;
let ad_id;
let adpreview;
let adpreview_id;

const logApiCallResult = (apiCallName, data) => {
  console.log(apiCallName);
  if (showDebugingInfo) {
    console.log('Data:' + JSON.stringify(data));
  }
};

const fields = [
];
const params = {
  'name' : 'Test Catalog',
};
product_catalog =  (new Business(business_id)).createProductCatalog(
  fields,
  params

);
product_catalog
.then((result) => {
  logApiCallResult('product_catalog api call complete.', result);
  product_catalog_id = result.id;
  const fields = [
  ];
  const params = {
    'name' : 'Test Feed',
    'schedule' : {'interval':'DAILY','url':'https://developers.facebook.com/resources/dpa_product_catalog_sample_feed.csv','hour':'22'},
  };
  return (new ProductCatalog(product_catalog_id)).createProductFeed(
    fields,
    params
  );
})
.then((result) => {
  logApiCallResult('product_feed api call complete.', result);
  product_feed_id = result.id;
  const fields = [
  ];
  const params = {
    'name' : 'All Product',
  };
  return (new ProductCatalog(product_catalog_id)).createProductSet(
    fields,
    params
  );
})
.then((result) => {
  logApiCallResult('product_set api call complete.', result);
  product_set_id = result.id;
  const fields = [
  ];
  const params = {
    'external_event_sources' : [pixel_id],
  };
  return (new ProductCatalog(product_catalog_id)).createExternalEventSource(
    fields,
    params
  );
})
.then((result) => {
  logApiCallResult('external_event_source api call complete.', result);
  external_event_source_id = result.id;
  const fields = [
  ];
  const params = {
    'objective' : 'PRODUCT_CATALOG_SALES',
    'promoted_object' : {'product_catalog_id':product_catalog_id},
    'status' : 'PAUSED',
    'name' : 'My Campaign',
  };
  return (new AdAccount(ad_account_id)).createCampaign(
    fields,
    params
  );
})
.then((result) => {
  logApiCallResult('campaign api call complete.', result);
  campaign_id = result.id;
  const fields = [
  ];
  const params = {
    'status' : 'PAUSED',
    'targeting' : {'geo_locations':{'countries':['US']}},
    'daily_budget' : '1000',
    'billing_event' : 'IMPRESSIONS',
    'bid_amount' : '20',
    'campaign_id' : campaign_id,
    'optimization_goal' : 'OFFSITE_CONVERSIONS',
    'promoted_object' : {'product_set_id': product_set_id},
    'name' : 'My AdSet',
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
  const params = {
    'url_tags' : 'utm_source=facebook',
    'object_story_spec' : {'page_id': page_id, 'template_data': {'call_to_action': {'type': 'SHOP_NOW'}, 'link': 'www.example.com', 'name': '{{product.name}} - {{product.price}}', 'description': '{{product.description}}', 'message': '{{product.name | titleize}}'}},
    'name' : 'My Creative',
    'product_set_id' : product_set_id,
    'applink_treatment' : 'web_only',
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
    'tracking_specs' : [ {'action_type': ['offsite_conversion'], 'fb_pixel': [pixel_id]} ],
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
.then((result) => {
  logApiCallResult('ad api call complete.', result);
  ad_id = result.id;
  const fields = [
  ];
  const params = {
    'ad_format' : 'DESKTOP_FEED_STANDARD',
  };
  return (new Ad(ad_id)).getPreviews(
    fields,
    params
  );
})
.then((result) => {
  logApiCallResult('adpreview api call complete.', result);
  adpreview_id = result[0].id;
})
.catch((error) => {
  console.log(error);
});