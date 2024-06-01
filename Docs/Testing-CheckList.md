# Classes
## ANProcessWebhookJob ( tested )
- This class is responsible for handling the incoming alerts from Ethoca
- It is a job class that is dispatched when an alert is received
- It is responsible for saving the alert to the database


## ProcessAlert
- This class is responsible for processing the alert
- It is responsible for finding Transaction in CRM ( Tested)
- Creating a new Transaction if not found ( Tested)
- checking if the Transaction company exists in bot database if not it creates a new company ( Tested)
- finding gateway related to transaction using midNumber (tested)
- check if gateway/merchant exists in database and creates a new one if not found (tested)
- add note to customer (tested)
- add `OTP` to customer (tested)
- find customer related to transaction using customer ID (tested)
- black list customer email, phone, and IP (tested)
- cancel Fulfillment (tested)
- get customer history (tested)
- confirm Fulfillment  cancel (tested)
## ProcessEthocaUpdate
