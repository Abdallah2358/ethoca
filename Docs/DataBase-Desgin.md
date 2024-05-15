# Tables 

## Alerts 
- [ethoca_alert_responses](database/migrations/2024_05_11_193455_create_ethoca_alert_responses_table.php)
  - used to store the responses of the ethoca alerts coming from the ethoca api `Ethoca360Alerts`    
- [ethoca_alerts](database/migrations/2024_05_11_193455_create_ethoca_alerts_table.php)
  - used to store the alerts coming from the ethoca api `Ethoca360Alerts`

## Acknowledgements
- [ethoca_acknowledgement_responses](database/migrations/2024_05_11_193455_create_ethoca_acknowledgement_responses_table.php)
  - used to store the responses of the ethoca acknowledgements coming from the ethoca api `Ethoca360Acknowledgements`
- [ethoca_acknowledgements](database/migrations/2024_05_11_193455_create_ethoca_acknowledgements_table.php)
  - used to store the acknowledgements sent to the ethoca api `Ethoca360Acknowledgements` and if this acknowledgement was successful or not

## Updates
- [ethoca_update_responses](database/migrations/2024_05_15_003621_create_ethoca_update_responses_table.php)
  - used to store the responses of the ethoca updates coming from the ethoca api `Ethoca360Updates`
- [ethoca_updates](database/migrations/2024_05_15_003621_create_ethoca_updates_table.php)
  - used to store the updates Sent to the ethoca api `Ethoca360Updates` and if this update was successful or not

## CRM Actions 
- [crm_actions](database/migrations/2024_05_15_003621_create_crm_actions_table.php)
  - used to store the actions that are performed on the CRM to handle the alerts

## Errors
- [ethoca_errors](database/migrations/2024_05_15_003621_create_ethoca_errors_table.php)
  - used to store any errors that 
    - may occur during the process of sending or receiving data from the ethoca api.
    - may occur during the process of performing actions on the CRM
