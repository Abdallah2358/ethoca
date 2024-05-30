# Project Summary 
## What is Done ?
- Database Design to save Alerts, CRM Action, Errors, LLC, Merchants, and Transactions 
  - Relations Between All these Entities 
- Logic :
  - Push Alert :
    - Receive alerts from Ethoca on `/EthocaAlertNotification` 
      - `EthocaAlertNotification` can be changed from [web.php](/routes/web.php)
      - Logic for handling received alert is on [ANProcessWebhookJob.php](app\Jobs\ANProcessWebhookJob.php) 

- Views :
  - Alerts :
    - All Alerts on `alerts/`
    - Alerts Summary per LLCs on `/alerts/companies`
      <!-- - Alerts Summary per LLC (single) on `/alerts/companies/{companyId}` (Not Done) -->
    - Alerts Summary per Merchants on `/alerts/merchants`
      - Alerts per Merchant (single) on `/alerts/merchants/{merchantId}`
- Testing Setup :
  - Mock need services like :
    - Ethoca Push Service using PostMan
    - KK Api using Mockoon 
    - Ethoca Update Service Using SoapUI

## What's left ?
- A way to link Merchants to The Alert
- A way to link merchants to their parent LLC
- Alerts Summary per LLC (single) on `/alerts/companies/{companyId}`
- a manual way to handle alerts
- through testing
- deployment documentation


## Blockers 
- Feedback is a bit slow