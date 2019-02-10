Amplify API

Introduction

This API describes the interfaces for interacting with the Outbrain Amplify product. More information about Amplify can be found on our website: http://www.outbrain.com/amplify.

At this time we are working with a select number of partners to integrate the Amplify API. To be considered for the Amplify API private beta please register for the beta plan.

If you have questions about getting started or using this API please contact amplifyapi@outbrain.com

If you have any questions, issues, feature requests as well as if you're interested in announcments of new features, please join our Google group:

https://groups.google.com/forum/#!forum/outbrain-amplifyapi

API Url

The base url for all API end-points is https://api.outbrain.com/amplify/v0.1/

Troubleshooting

If you're experiencing any issues or any other difficulties, please post a new topic in our Google group: https://groups.google.com/forum/#!forum/outbrain-amplifyapi

Please attach to your post the AMPLIFY-REQUEST-ID header from the returned response. This header allows us to identify your unique request and helps us to reply quicker and more efficiently.

Reference

Overview and Entities

The API is RESTful and is structured into the following core entities:

Marketer: A customer account.

Budget: A budget that contains money to be spent on one or more campaigns.

Campaign: A collection of promoted links.

PromotedLink: A single piece of promoted content.

PerformanceBy*: A variety of analytics entities to retrieve metrics.

The following operations may be performed via this API:

Entity	Function
Marketer	Read
Budget	Read, Create, Update
Campaign	Read, Create, Update
PromotedLink	Read, Create, Update
Performance*	Read
Performance* refers to the many entities that represent reporting data. For example, here are a few of our 9 different reporting endpoints:

Performance for a Marketer per day

Performance for a Campaign per day

Performance for a PromotedLink per day

HTTP Requests

We follow RESTful conventions of using HTTP methods to determine the type of action requested:

HTTP Method	Action
GET	Retrieve a resource or a list of resources
POST	Create a resource.
PUT	Update a resource.
When issuing a POST/PUT request that includes a json of the created/updated entitiy the following header must be included in the request:

Content-Type: application/json
HTTP Status Code Replies

HTTP Status Code	Meaning	Description
200	OK	The request was successful.
400	Bad Request	The request could not be understood or was missing required parameters
401	Unauthorized	Authentication failed or user doesn't have permissions for requested operation
403	Forbidden	Access denied
404	Not Found	Resource was not found
Error Handling

When the API returns a status code indicating an error (400, 401, 403, 404) it will also include the JSON describing the error. For example:

{
   "moreInfo" : "get-budget",
   "errorMessage" : "access denied"
}
Authentications

Token

Obtaining a token should be your 1st step toward using Amplify API. You need to include it in all further requests using the HTTP Header OB-TOKEN-V1. In order to protect your privacy, the tokens are valid for a period of 30 days. You will not be able to access Amplify API using an expired token. You can generate as many tokens as you wish. Generating a new token does not invalidate older tokens. When a user password or email are changed in the Account Page all tokens generated prior to the change are revoked and a new token must be generated.

How to obtain a token ?

There are 2 ways to obtain a token

via web interface

Simply go to your Account Page fill in your password, hit generate, and you will get your token. form If you can't see this form, this means that your username doesn't have access to the Amplify API. To be considered for the Amplify API private beta please register to the beta plan.

via API

Attributes

via API

Yes, you can obtain a new token using the Amplify API. Why? API is all about automation, so why not obtain a new token using the API before your token expires?

This API transaction is the only one in which you need to supply your Outbrain credentials.

Your credentials should be sent using Basic Authentication.

This means that the HTTP Request should include a header:

named Authorization value: BASIC BASE-64-ENC(YOUR_OUTBRAIN_USER_NAME:YOUR_OUTBRAIN_PASSWORD)

Since all communication to Amplify API are HTTPS, your credentials are safe.

You can try it using curl command (add -v to actually see the Authorization HTTP header:

curl -u YOUR_OUTBRAIN_USER_NAME:YOUR_OUTBRAIN_PASSWORD https://api.outbrain.com/amplify/v0.1/login

Users

Users represent a single person's access to Outbrain services and to the site my.outbrain.com. Users have permissions on one or more Marketer accounts.

Users cannot be managed via this API and must be created with the help of your Outbrain contact.

Marketers

Marketers are representatives of Outbrain customers. A single Marketer object may control campaigns for more than one end-buyer.

Marketers cannot be created via the API and must be created via the site or by an Outbrain Account Manager.

Marketer

Attributes

The Marketer object has the following attributes:

Property	Type	Semantic	Example
id	String	The Marketer's id	"00f4b02153ee75f3c9dc4fc128ab041962"
name	String	The Marketer's name	"my marketer"
enabled	Boolean	true when the Marketer is enabled	true
lastModified	Time	The time when the Marketer was last modified.	"2013-03-16 10:32:31"
creationTime	Time	The time when the Marketer was created.	"2013-01-14 07:19:16"
blockedPublishers	Array	Array of publishers ids that will be blocked. The publishers ids can be retrieved from Performance for marketer per publisher	
Retrieve a Single Marketer

Update a Single Marketer

Marketers collection

Attributes

List Marketers

Retrieve all Marketers associated with the current user (as identified by the token in the OB-TOKEN-V1 HTTP header).

Budgets

Budget objects represent an amount of money to be spent via the Amplify platform. A single Budget may be shared among Campaigns (also known as a "shared budget"), though it is more common to have just one Campaign per Budget.

For convenience, Budget objects are often embedded inside the Campaign objects.

Budget

Attributes

The Budget object has the following attributes:

Propery	Type	Semantic	Example
id	String	The id of this Budget. read-only	"00f4b02153ee75f3c9dc4fc128ab041962"
name	String	The name of this Budget.	"First quarter budget"
shared	Boolean	Whether the Budget is shared between Campaigns, provided for convenience based on the number of Campaigns associated to this Budget. read-only	true
amount	Money	The monetary amount of this Budget	2000.00
currency	String	The currency denomination applied to the budget amount read-only	"USD"
amountRemaining	Money	The unspent monetary amount remaining on this Budget. read-only	150.00
amountSpent	Money	The spent monetary amount of this Budget. read-only	1850.00
creationTime	Time	The time when this Budget was created. read-only	"2013-01-14 07:19:16"
lastModified	Time	The last modification date of this Budget. read-only	"2014-01-15 12:24:01"
startDate	Date	The date this Budget is scheduled to begin spending.	"2014-01-15"
endDate	Date	The date this Budget is scheduled to stop spending. If runForever is true this will not be used.	"2014-01-17"
runForever	Boolean	Designates whether the budged has an end date In case of true, 'endDate' attribute will not be part of the Budgets' attributes.	true
type	Budget Type	Controls on which period the Budget refreshes	"MONTHLY"
pacing	Pacing Type	Controls how fast the Budget will be spent	"AUTOMATIC"
dailyTarget	Money	The maximum amount of spend that is allowed per day. Relevant for DAILY_TARGET pacing.	100.00
maximumAmount	Money	The maximum amount allowed if defined read-only	100.00
Retrieve a single Budget

Update an existing Budget

To update a Budget send a JSON with the updated value for one or more of the updatable Budget attributes.

All attribute values left unset in this PUT will remain unchanged.

Only the following Budget properties are updatable:

amount - The amount must be lower than the maximumAmount and higher than 10

startDate - The start date cannot be in the past. Start date cannot be modified after budget has started (ie: start date already passed)

endDate - The end date must be after the start date, and cannot be in the past.

runForever - If set to 'true', the 'endDate' attribute should not be included as it is meaningless. The current 'endDate' attribute of the Budget (if exists) will be discarded.
In case of passing 'false', 'endDate' attribute must be also passed.

dailyTarget - The maximum amount of spend that is allowed per day. Relevant only if DAILY_TARGET pacing is used.

Budgets Collection

Attributes

Retrieve a collection of all Budgets for the specified Marketer.

The Budgets Collection resource has the following attributes:

count - Number of Budgets found for this Marketer

budgets - Array of budget objects

List Budgets for a Marketer

Create a Budget for a Marketer

To create a new budget use the following properties

Property	Type	Semantic	Example
name	String	The name of this Budget. Must be unique per each marketer. Maximum length is 100 characters.	"First quarter budget"
amount	Money	The monetary amount of this Budget	2000.00
startDate	Date	The date this Budget is scheduled to begin spending.	"2014-01-15"
endDate	Date	The date this Budget is scheduled to stop spending. If runForever is true this will not be used.	"2014-01-17"
runForever	Boolean	Designates whether the budget has an end date. In case of true, endDate attribute will be ignored.	true
type	Budget Type	Controls on which period the Budget refreshes	"MONTHLY"
pacing	Pacing Type	Controls how fast the Budget will be spent. For budget type DAILY only SPEND_ASAP pacing is supported.	"AUTOMATIC"
dailyTarget	Money	The maximum amount of spend that is allowed per day. Mandatory only in case the pacing is set to DAILY_TARGET	100.00
Campaigns

Campaigns contain many PromotedLinks and define Budget, targeting, cpc, etc. Campaign settings apply to the PromotedLinks contained in a specific Campaign.

Campaign

Attributes

The Campaign object has the following attributes:

Property	Type	Semantic	Example
id	String	campaign id.	"00f4b02153ee75f3c9dc4fc128ab041962"
name	String	campaign name.	"My Campaign"
marketerId	String	marketer id.	"00f4b02153ee75f3c9dc4fc128ab041963"
enabled	Boolean	is campaign enabled.	true
cpc	Money	cost per click.	0.58
minimumCpc	Money	the minimal possible cost per click. read-only	0.2
currency	String	The currency of the Budget amount. read-only	"USD"
autoArchived	Boolean	designates whether this Campaign is automatically archived. read-only	true
targeting	Targeting Object	See Targeting object.	
feeds	Array of RSS feeds	Each feed contains the URL of the rss feed.	
autoExirationOfPromotedLinks	Numeric	A number specifying within how many days PromotedLinks are expired - PromotedLinks expire X days after their publication day (if known), otherwise - X days after their creation date.	7
contentType	Content Type	The Campaign's content type.	"VIDEO"
budget	Budget	see Budget entity. read-only	
suffixTrackingCode	String	a parameter list that will be appended to the Campaign's PromotedLinks.	"utm_source=outb&utm_medium=cpc&utm_campaign=Celebrity-2-1"
lastModified	Time	The time when the Campaign was last modified. read-only	"2013-03-16 10:32:31"
creationTime	Time	The time when the Campaign was created. read-only	"2013-01-18 07:19:16"
liveStatus	Live Status	holds Campaign's on air and spend information. read-only.	
Retrieve a Single Campaign

Update an Existing Campaign

To update a campaign send a JSON with the updated value for one or more of the updatable campaign attributes.

All attribute values left unset in this PUT will remain unchanged.

Only the following Campaign properties are updatable:

name - Maximum length is 100 characters

enabled

cpc - The cpc must be higher than the minimumCpc

targeting.platforms - See Targeting object

suffixTrackingCode - Tracking code parameters that will be appended to the Campaign's PromotedLinks.

Campaigns

Attributes

Create a new Campaign

Property	Type	Semantic	Example
name	String	The name of this Campaign. Must be unique per each marketer. Maximum length is 100 characters.	"My Campaign"
budgetId	String	The id of the budget to associate the new campaign with.	"00f4b02153ee75f3c9dc4fc128ab041962"
enabled	Boolean	Is the campaign enabled	true
cpc	Money	Cost per click. See Currencies for valid cpc values.	0.58
targeting	Targeting Object	See Targeting object	
feeds	String array	An array of strings where each element contains the URL of the rss feed. optional	
suffixTrackingCode	String	Tracking code parameters that will be appended to the Campaign's PromotedLinks. optional	"utm_source=outb&utm_medium=cpc&utm_campaign=Celebrity-2-1"
Campaigns Collection via Budget

Attributes

List all Campaigns associated with a Budget

Campaigns Collection via Marketer

Attributes

Collection of all campaigns for a specific Marketer.

The campaigns collection resource has the following attributes:

count - Number of campaigns for this Marketer

campaigns - Array of campaigns

List all Campaigns associated with a Marketer

PromotedLinks

A PromotedLink is the entity that describes a single piece of promoted content.

PromotedLink

Attributes

The PromotedLink object has the following attributes:

Property	Type	Semantic	Example
id	String	The ID of this PromotedLink read-only	"00f4b02153ee75f3c9dc4fc128ab041962"
campaignId	String	The ID of the campaign to which the PromotedLink belongs read-only	"00f4b02153ee75f3c9dc4fc128ab041963"
text	String	The text of the PromotedLink	"Google to take over huge NASA hangar, give execs' private planes a home"
lastModified	Time	The time when the PromotedLink was last modified. read-only	"2013-03-16 10:32:31"
creationTime	Time	The time when the PromotedLink was created. read-only	"2013-01-14 07:19:16"
url	String	The URL visitors will be sent to upon clicking the PromotedLink. read-only	"http://www.engadget.com/2014/02/11/nasa-google-hangar-one/"
siteName	String	The name of the publisher the PromotedLink URL points to. read-only	"cnn.com"
sectionName	String	The section name of the site the PromotedLink URL points to. read-only	"Sports"
status	PromotedLink Review Status	The review status of the PromotedLink. read-only	"PENDING"
imageUrl	String	The URL of an image to be used for the specified PromotedLink. Valid only for Post requests.	"http://upload.wikimedia.org/wikipedia/commons/8/88/Bright_red_tomato_and_cross_section02.jpg"
cachedImageUrl	String	The URL of the PromotedLink's image, cached on Outbrain's servers. Valid only for GET requests.	"http://images.outbrain.com/imageserver/v2/s/gtE/n/plcyz/abc/iGYzT/plcyz-f8A-158x110.jpg"
enabled	Boolean	Designates whether this PromotedLink will be served.	true
archived	Boolean	Designates whether this PromotedLink is archived.	true
documentLanguage	Language code	The 2-letter code for the language of this PromotedLink (via the PromotedLink's URL).	"EN"
Retrieve a Single PromotedLink

Update an Existing PromotedLink

To update a PromotedLink send a JSON with the updated value for one or more of the allowed PromotedLink attributes.

All attribute values left unset in this PUT will remain unchanged.

Only the following PromotedLink attributes are updatable:

enabled
Create a PromotedLink

Attributes

Create a PromotedLink

You can create a new promoted link and associate it with an existing campaign. AmplifyAPi allows you to add your own headline and image for the newly created promoted link. If you'd like to set your own image, then you can do so by providing an http link for the hosted image or by uploadig an image file.

linking to a hosted image

POST a JSON hash with the following attributes:

text - optional, if not provided, the title will be set via our backend crawler

url - mandatory

enabled - mandatory

imageUrl - optional, if not provided, the image will be set via our backend crawler

Uploading a local image file

POST a multipart/form-data request using a boundary token to separate between the following attributes:

text - optional, if not provided, the title will be set via our backend crawler

url - mandatory

enabled - mandatory

image - optional, the image content in bytes, if not provided, the image will be set via our backend crawler

PromotedLinks Collection

Attributes

Collection of all PromotedLinks for the specified Campaign.
Results can be filtered by supplying additional query parameters.

The PromotedLinks collection resource has the following attributes:

count - Number of PromotedLinks returned in the PromotedLinks array included in this reply.

promotedLinks - Array of PromotedLinks objects.

totalCount - Total number of PromotedLinks regardless of 'limit' and 'offset' parameters, used for pagination.

List PromotedLinks for Campaign

Performance Reporting

There are a multitude of reporting endpoints that allow you to retrieve metrics at exactly the right level of granularity.

All reporting endpoints share a common response structure, defined below.

Response structure

Property	Type	Semantic
overallMetrics	Object	Computed metrics for the set of data returned in this response.
currencyName	String	The three letter abbreviation for the currency used in all money values.
totalDataCount	Number	Total number of results returned.
details	Array	An array of results.
Overall Metrics structure

Property	Type	Semantic	Example
cost	Money	Total amount of money spent	1234.5
eCpc	Money	The calculated average CPC (Cost Per Click) across the entire query range: total cost / total clicks.	0.12
ctr	Number	The average CTR (Click Through Rate) calculated across the entire query range: total clicks / total impressions.	0.55
cpa	Number	The average CPA (Cost Per Acquisition) calculated across the entire query range: total cost / total # of conversions.	0.55
conversion	Number	The total number of conversions across the entire query period.	0.55
clicks	Number	Total PromotedLinks clicks across the entire query range.	12345.00
impressions	Number	Total number of PromotedLinks impressions across the entire query range.	98765.00
Performance for a Marketer per day

Attributes

Retrieve performance statistics for a Marketer per day

Performance for a Marketer per month

Attributes

Retrieve statistics for Marketer per month

Performance for a Marketer per PromotedLink

Attributes

Retrieve performance statistics for a Marketer per a PromotedLink

Performance for a Marketer per Campaign

Attributes

Retrieve performance statistics for a Marketer per Campaign

Performance for a Campaign per day

Attributes

Retrieve performance statistics for a Campaign per day

Performance for a Campaign per month

Attributes

Retrieve performance statistics for a Campaign per month

Performance for a Campaign per PromotedLink

Attributes

Retrieve performance statistics for a Campaign per PromotedLink

Performance for a PromotedLink per day

Attributes

Retrieve performance statistics for a PromotedLink per day

Performance for a PromotedLink per month

Attributes

Retrieve performance statistics for a PromotedLink per month

Publisher Performance Reporting

Publisher: A content site that displays Outbrain recommendations on which your promoted content appeared.

Section: A specific content section within the publisher's site such as sports, business etc.

*Please note, all publisher and section reports have a 24 hour delay. They are updated every day at 3am EST.

All reporting endpoints share a common response structure, defined below.

Response structure

Property	Type	Semantic
overallMetrics	Object	Computed metrics for the set of data returned in this response.
totalDataCount	Number	The total number of results in the dataset, regardless of offset and limit.
details	Array	An array of results.
Overall Metrics structure

Property	Type	Semantic	Example
clicks	Number	Total PromotedLinks clicks across the entire query range.	
Performance for marketer per publisher

Attributes

Retrieve performance statistics for a marketer per publisher

Performance for marketer per section

Attributes

Retrieve performance statistics for a marketer per section

Performance for campaign per publisher

Attributes

Retrieve performance statistics for a campaign per publisher

Performance for campaign per section

Attributes

Retrieve performance statistics for a campaign per section

Currencies

Metadata about the available currencies


Currencies collection

Attributes

The Currencies is a collection of currency objects with the following attributes

Propery	Type	Semantic	Example
code	String	3 letters ISO 4217 currency code	USD
name	String	Currency name	United States dollar
minimumCpc	Money	The minimal CPC value for thic currency, relevant for creating or updating campaign	0.03
maximumCpc	Money	The maximal CPC value for thic currency, relevant for creating or updating campaign	2.0
List Currencies

Reference Types


Date

All date attributes are in 'yyyy-MM-dd' format. E.g. January 11th, 2015 is: 2015-01-26.


Time

All time attributes are in 'yyyy-MM-dd HH:mm:ss' format, where the time is with accordance to EST with 0..23 hour representation. E.g. 2013-02-28 17:43:19.


Money

Money is a decimal number with precision of 2 digits after the decimal point.


Platform

A platform is one of the following:

DESKTOP - The content was consumed via desktop.

MOBILE - The content was consumed via mobile.


Review Status

A PromotedLink review status is one of the following:

APPROVED - PromotedLink content is approved

PENDING - PromotedLink content is in the proccess of being reviewed

REJECTED - PromotedLink content is rejected


Campaign On Air Reason

A Campaign on air reason is one of the following:

CAMPAIGN_DISABLED - The campaign is disabled

CAMPAIGN_FLY_DATES_PAST - The campaign has reached its end date

CAMPAIGN_FLY_DATES_FUTURE - The campaign hasn't started

CAMPAIGN_DAILY_CAPPED - The daily campaign budget has depleted

CAMPAIGN_DAILY_CAPPED_MONTHLY - The monthly campaign daily budget has depleted

CAMPAIGN_DAILY_CAPPED_CAMPAIGN - The daily campaign budget has depleted, and the budget type is campaign

CAMPAIGN_BUDGET_DEPLETED - The entire campaign's budget has depleted

CAMPAIGN_ALL_PROMOTED_LINKS_DISABLED - All the campaign's promoted links are disabled

CAMPAIGN_ALL_PROMOTED_LINKS_REJECTED - All the campaign's content was rejected

ALL_PROMOTED_LINKS_EXPIRED - All campaign content has expired

ALL_PROMOTED_LINKS_PENDING - All the campaign's content was rejected

WAITING_FOR_START_HOUR - The campaign will resume tomorrow/today

NO_RUNNING_PROMOTED_LINKS - No content is currently running


Budget Type

A Budget type is one of the following:

CAMPAIGN - The budget is one-time and does not repeat

MONTHLY - The budget repeats per month

DAILY - The budget repeats per day


Pacing Type

A pacing type is one of the following:

SPEND_ASAP - The budget should be spent ASAP, with no daily capping

AUTOMATIC - Allow the Outbrain engine to decide on the budget spend pacing

DAILY_TARGET - The budget should be spent on a daily basis, with a limit at this target amount


Content Type

A content type is one of the following:

ARTICLES - The content of this campaign is articles

VIDEO - The content of this campaign is video

ALL - The content is a mixture articles and videos


Live Status

A Live Status object describes the state of a campaign at an instant in time with the following attributes:

Property	Type	Semantic	Example
campaignOnAir	boolean	Is the campaign on air	false
onAirReason	Campaign On Air Reason	The reason for the campaign on air status	"Waiting For Start Hour"
amountSpent	Money	Money spent today for Daily budget, this month for Monthly budget, or from the beginning of history for Campaign budget.	450.00
Live Status objects are correct only for the instant they are returned. That is, a campaign that is on-air when the request is made may be off-air 10 minutes later.


Sort Field

Sort the results by one of the metric columns with -/+ to indicate descending/ascending sorts. Only one sort is possible per request.


Reference Objects

These objects have endpoints describing their options and are used to configure Budgets and Campaigns.


Targeting Object

The Targeting object is simply a container for many Platform targeting objects. This is the object that is set on a campaign to apply targeting. This container has the following attributes:

Property	Type
Platforms	array of Platform
