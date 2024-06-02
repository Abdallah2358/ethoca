- receive alerts data and store it
  - how many alerts we receive ( how many came in)
  - The intended action for each alert 
    - The action that was taken
      - The action that was successful
      - The action that failed
        - The error that caused the action to fail
- actions
  - append transaction to bot database
  - make sure action was successful
  - store datetime and when action finished
  - when crm was updated and we got confirmation it was updated
  
- errors
  - Store all Errors

- Close/Update Ethoca Alert

- Reports 
  - Refunded Transactions
    - Successful Refunds
    - Failed Refunds
      - Errors Related to Failed Refunds
  - Handled Alerts 
  - Updated Alerts
  - paid and not paid alerts
    - per company
      - per segment of the company or website


- Scale Issue 
  - Mapping between Bot Database and other Databases/Apis
  - Note :
    - Using AI to check data and put it in the right place
