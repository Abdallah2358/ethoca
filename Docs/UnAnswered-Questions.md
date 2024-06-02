- [Needed Credentials](#needed-credentials)
- [Confusion](#confusion)
  - [KK API Response Structure](#kk-api-response-structure)
  - [Gateway and Merchant Relationship on CRM](#gateway-and-merchant-relationship-on-crm)
  - [Transactions](#transactions)
  - [Email and Phone Number Blacklist](#email-and-phone-number-blacklist)
- [Missing Data](#missing-data)
  - [IP Address](#ip-address)
  - [Paid and Not Paid Alerts](#paid-and-not-paid-alerts)
- [Reports Views](#reports-views)
- [Notes](#notes)

# Needed Credentials
- [ ] **Ethoca Username**
- [ ] **Ethoca Password**
- [ ] **KK LoginId**
- [ ] **KK Password**

# Confusion 
## KK API Response Structure
- Some responses has a `message` field that some times contain `totalResults`, `resultsPerPage`,`page`,`data` ( which contains array of data ), `params`, `errors`, `sortBy`,`sortDir`, `queryArgs` 
- Sometimes it contains `message` that contains the actual response data directly.
> Is this dynamic based on request Parameters  or is there a specific pattern that I can follow to know what to expect in the response ?

## Gateway and Merchant Relationship on CRM 
- [ ] **What is the relationship between Gateway and Merchant?**
  - It Seems that Gateway is like a payment processor and Merchant is the actual business that is accepting payments.
  - In KK api Docs 
    - there is a `midNumber` field in the ***transaction*** and ***gateway*** objects. is this field used to find the gateway related to the transaction ?
    - there is a `billerId` field in the ***gateway*** object. Is this field used to find the merchant related to the gateway using `merchantId`? 
    - In ***gateway*** 
      - `descriptor` : is this merchant descriptor ?
      - `gatewayId` : is this sub merchant Id or just a gateway for paying ?
      - `gatewayData` : in the response I see  "{\"CompanyID\":\"CompanyID\",\"Password\":\"Password\",\"Hashcode\":\"Hashcode\"}" what does that stand for 
      - `merchantCategoryId` : is this the same as mcc ?
      - `gatewayName` : how is this different from title ?
      - 
BUT what I am missing is how Am I going to Tie the Transaction to the merchant, I see these fields in transaction response :
- merchantDescriptor ( is this the same as Descriptor form gateway response ? )
- merchantId ( only see it on the transaction but not on the gateway ! )
- merchantTxnId ( this I assume the transaction id on the merchant portal ?! redundant to my app)
- midNumber ( this the only field That I am sure is common between transactions and gateway this probably why you said use it but the reason I am still asking is in the api documentation all the example responses had this field as null so is this just because it is an example or does it mean that the field is optional or legacy or what ?! )

## Transactions 
- We have a `raw_data` table that used to store alot of transactions 
  - Are all these transactions fraudulent ?
  - Or Did it store all the transactions and then we filter the fraudulent ones ?
  - Maybe this question does not really matter since if we only care about fraudulent transactions in the bot ?!
## Email and Phone Number Blacklist
- On the Transaction Object and Customer Object there is an `emailAddress` and `phoneNumber` fields. 
  - Do we block both of them ? 
  - or only one of them ?
    - If only one of them, which one ?
  - Or is it not important for the bot ?

# Missing Data 
## IP Address
- I checked the Transaction and the Customer objects and I did not find any field for the IP address of the transaction. 
  - Is this field not available in the API ?
  - Or is it available in another object ?
  - Or is it not important for the bot ?
## Paid and Not Paid Alerts 
- I see in the video that we want the report to show real-time alerts that are paid and not paid. 
  - How do I know if the alert is paid or not ?
# Reports Views 
- I know we want to show the alerts per LLC and merchant but what data do we want to show in these views ? 
- In the video I saw that we want to show the alerts 
  - that are paid and not paid. 
    - Then per Company
    - Then per segment of the company or website 
- Missing : 
  - using KK API Docs What is a segment of the company ?
    - Is it the same as gateway ?
  
# Notes
  - `Transaction` object from endpoint `transactions/query/`[link](https://apidocs.konnektive.com/#e9a7caf1-825c-485a-b7c4-b3cae30384f3)
  - `Customer` object from endpoint `customer/query/` [link](https://apidocs.konnektive.com/#dd99c63c-2a6d-4fe0-9657-acfe49d90ac9)
  - I need it to block it in the future if it is a fraudulent IP address.
  





