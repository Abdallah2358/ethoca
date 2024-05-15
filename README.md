# Description
This is laravel project for sending soap api request to ethoca web service and log the response in database.

# Requirements
1. PHP + 8.0
2. Composer
3. MySQL

# Installation
1. Clone the repository
2. Run `composer install`
3. create a `.env` file from a copy of [.env.example](.env.example)
4. fill the required fields 
   - ETHOCA_WSDL_URL 
        > The url/path to  `EthocaAlerts.wsdl` either sandbox or production 
   - ETHOCA_USERNAME 
        > The User Name given by ethoca for api auth
   - ETHOCA_PASSWORD
        > The Password given by ethoca for api auth

# Usage 

The app uses Task Scheduling in Laravel ( similar to cron jobs) to pull alerts form Ethoca every certain interval 
 
## Single Pull
- run
    ```bash
        php artisan app:make-soap-alerts-request
    ```
## Scheduled pull
- run 
    ```bash
        php artisan schedule:work
    ```
