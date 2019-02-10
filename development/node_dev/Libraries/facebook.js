const bizSdk = require('facebook-nodejs-business-sdk');

const accessToken = 'EAAEcCD4ICi0BAJvaY2J2LPK5ellyzP2sQZCCEFVN1AvyiP9AY8pLABtjdeC2E7ZAzLGqZAynZAEhuAhLSg6AwJh7ZB6qBcIlji4wZBqeX7kX2FLn7zIhpr8QZCzTbB1m1FqYeHHzIzkj16JZAN2sxbQWHVdix9G4YwsWhlqwI980NwZDZD';
const accountId = 'act_1369819516488015';

const FacebookAdsApi = bizSdk.FacebookAdsApi.init(accessToken);
const AdAccount = bizSdk.AdAccount;
const Campaign = bizSdk.Campaign;
const api = bizSdk.FacebookAdsApi.init(accessToken);
const account = new AdAccount(accountId);
const showDebugingInfo = true;
if (showDebugingInfo) {
  api.setDebug(true);
}

// List campaigns
const listCampaigns = async () => {
  try {
    let rs  = await account.getCampaigns(
      [Campaign.Fields.name],
      {
        limit: 2
      }
    );
    rs.forEach((camp) => {console.log(camp.id +'=>'+camp.name)});
  } catch (e) {
    console.log(e);
  }
}
listCampaigns();
// Create a new campaign
const createCampaign = async () => {
  try {
    let rs = await account.createCampaign(
      [],
      {
        [Campaign.Fields.name]: 'Hari Bol',
        [Campaign.Fields.status]: Campaign.Status.paused,
        [Campaign.Fields.objective]: Campaign.Objective.page_likes
      }
    );
    console.log(JSON.stringify(rs));
  } catch (e) {
    console.log(e);
  }
}
//createCampaign();
// Update a campaign
const updateCampaign = async (campaign_id) => {
  try {
    let rs = await new Campaign(campaign_id,
      {
        [Campaign.Fields.name]: 'Node Campaign',
        [Campaign.Fields.id]: campaign_id
      }
    ).update();
    console.log(JSON.stringify(rs));
  } catch (e) {
    console.log(e);
  }
}
// updateCampaign('23843277064420652');
// Delete a campaign
const deleteCampaign = async (campaign_id) => {
  try {
    let rs = await new Campaign(campaign_id).delete();
    console.log(JSON.stringify(rs));
  } catch (e) {
    console.log(e);
  }
}
//deleteCampaign('23843277064420652');
