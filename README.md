- [Project Description](#project-description)
- [Requirements](#requirements)
- [Installation](#installation)
- [Ethoca Pull Config](#ethoca-pull-config)
  - [Usage](#usage)
    - [Single Pull](#single-pull)
    - [Scheduled pull](#scheduled-pull)
- [Ethoca Push Config](#ethoca-push-config)
- [Ethoca Push Webhook](#ethoca-push-webhook)
  - [Summary](#summary)
  - [Configuration](#configuration)
  - [Processing Alert](#processing-alert)
  - [Testing](#testing)
    - [Push Alerts](#push-alerts)
      - [Tools](#tools)
      - [Steps](#steps)
        - [Runing the Server](#runing-the-server)
        - [Postman](#postman)
        - [Mockoon](#mockoon)
        - [SoapUI](#soapui)
      - [Important Notes](#important-notes)

# Project Description
This is laravel project is a bot for 
1. handling ethoca alerts by either receiving the alerts from ethoca or pulling them then performing certain action on Koniktive using there api and make sure to save all the steps done by the bot starting from receiving the alerts to performing the actions to updating the alert status

2. provide some helpful visualization of all the steps done by the bot this includes successful and failed steps to get help evaluate the performance of the bot  


# Requirements
1. PHP + 8.0
2. Composer
3. MySQL

# Installation
1. Clone the repository
2. Run `composer install`
3. create a `.env` file from a copy of [.env.example](.env.example)
4. fill the required fields including these custom one 
   - ETHOCA_WSDL_URL 
        > The url/path to  `EthocaAlerts.wsdl` either sandbox or production 
   - ETHOCA_USERNAME 
        > The User Name given by ethoca for api auth
   - ETHOCA_PASSWORD
        > The Password given by ethoca for api auth
   - KONNEKTIVE_API_URL
        > The url to connect to CRM API
    - KONNEKTIVE_LOGIN_ID
        > The login id for CRM API
    - KONNEKTIVE_PASSWORD
        > The password for CRM API

# Ethoca Pull Config 
## Usage 

The app uses Task Scheduling in Laravel ( similar to cron jobs) to pull alerts form Ethoca every certain interval 
 
### Single Pull
- run
    ```bash
        php artisan app:make-soap-alerts-request
    ```
### Scheduled pull
- run 
    ```bash
        php artisan schedule:work
    ```

# Ethoca Push Config 
- The Defualt route for ethoca push is `/EthocaAlertNotification` this is the endpoint where ethoca will send the alerts to.
  - If you wish to change it please Follow these steps;
    - Change the route in [web.php](routes/web.php#L35) to your desired route 
    - add this route to [VerifyCsrfToken.php](app/Http/Middleware/VerifyCsrfToken.php#L15)

# Ethoca Push Webhook
## Summary
- The Ethoca Push is handled by a Webhook using `Spatie\WebhookClient` package

## Configuration
The Webhook is configured in [config/webhook-client.php](config/webhook-client.php) under configs array key `Ethoca-Alert-Notification` each key has a comment that explains the configuration but our main concern is `process_webhook_job` key which is the job that will be dispatched when the webhook is hit.

## Processing Alert
- Once The webhook `Ethoca-Alert-Notification` is hit the job [ANProcessWebhookJob](app\Jobs\ANProcessWebhookJob.php) (`AN` short for `alert notification`) will be dispatched which does the following;
    - Log the request in the database
    - Check if the alert is already received and saved in the database
    - saves the alert in the database
    - Then Dispatches a job [ProcessAlert](app\Jobs\ProcessAlert.php) which does the following;
        - Check if the alert is valid by searching for a related Transaction
        - if found add 2 notes to customer one for the alert and one `OTP`
        - get the customer details from CRM API
        - blacklist the customer email, phone, and IP
        - cancel all future orders for the customer
        - cancel all fulfillment for the customer
        - refund amount of the transaction
        - check the customer actions history to confirm all the actions are done
        - update the alert handled status in the database
        - dispatch a job [ProcessUpdateEthoca](app\Jobs\ProcessUpdateEthoca.php) which does the following :
          - creates an ethoca update in database to log that an update was triggered
          - send the update to ethoca
          - log the response from ethoca
          - check if the update was successful
          - update the alert update status in the database

## Testing
### Push Alerts 
#### Tools 
- [Postman](https://www.postman.com/downloads/) 
- [Mockoon](https://mockoon.com/download/)
- [SoapUI Open Source]()

#### Steps
##### Runing the Server
Start by running the server using 
```Bash
    php artisan serve
```
##### Postman
Post Man is here used to mock the ethoca push alerts
- Create a new post request to `http://localhost:8000/EthocaAlertNotification`
- Using the raw body and select `JSON` from the dropdown
- Here is an Alert Example Pay Load 
    ```json
    {
        "ethoca_id": "kEYWGEwlBpWqfthbLEbKIXYTC",
        "type": "issuer_alert",
        "alert_timestamp": "2018-02-27T04:33:04.000Z",
        "age": "24",
        "issuer": "Bank of ABC",
        "card_number": "1111222233334444",
        "card_bin": "111122",
        "card_last4": "4444",
        "arn": "64738272371643523456435",
        "transaction_timestamp": "2018-02-28T04:33:04.000Z",
        "merchant_descriptor": "Pull",
        "member_id": 585,
        "mcc": "5561",
        "amount": 10.00,
        "currency": "USD",
        "transaction_type": "eCommerce",
        "initiated_by": "issuer",
        "is_3d_secure": "not_available",
        "source": "Issuer",
        "auth_code": "12345678",
        "merchant_member_name": "Ethoca Member",
        "transaction_id": "abcd1234567890",
        "chargeback_reason_code": "A890",
        "chargeback_amount": 340.60,
        "chargeback_currency": "USD"
    }
    ```

##### Mockoon
Mockoon is used to mock the crm api response because we dont want to test our app with the real crm api
- press `File`
- press `Open local Environment` or press `Ctrl + O`
- select the [crm-api-mockoon.json](Docs\mockoon\crm-apis.json) file
- Start the environment

##### SoapUI
SoapUI is used to mock the ethoca Update alerts response since it can only done using soap request

- Create a new SOAP project
- when asked for the initial wsdl browse to the [EthocaAlerts-Sandbox.wsdl](public\EthocaAlerts-Sandbox.wsdl) file 
- right click on `Ethoca360AlertsUpdate` and select `Add to MockService` chose the name you want
- right click on the mock service and select `Show MockService Editor`
- then press the green play button to start the mock service
- go to response 1 and change it to the values that match the response you want to test
  - example :
    ```XML
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns="http://schemas.ethoca.com/E360v1/xml">
        <soapenv:Header/>
        <soapenv:Body>
            <ns:Ethoca360AlertsUpdateResponse majorCode="0">
                <!--Optional:-->
                <ns:AlertUpdateResponses>
                    <!--1 or more repetitions:-->
                    <ns:AlertUpdateResponse ethocaID="kEYWGEwlBpWqfthbLEbKIXYTC" status="success">
                    </ns:AlertUpdateResponse>
                </ns:AlertUpdateResponses>
            </ns:Ethoca360AlertsUpdateResponse>
        </soapenv:Body>
        </soapenv:Envelope>
    ```
#### Important Notes
All The Pay load must have: 
- Same EthocaId
- Same Card Bin
- Same Card Last4