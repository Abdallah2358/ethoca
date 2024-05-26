<?php

namespace App\Models;

use App\Models\Traits\HasError;
use Database\Factories\RequestFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

class EthocaRequest extends Model
{
    use HasFactory, HasError;
    protected $table = 'ethoca_requests';
    protected $guarded = [];
    protected $appends = ['alerts'];
    public function ethocaResponse(): HasOne
    {
        return $this->hasOne(EthocaResponse::class);
    }
    public function errors(): Attribute
    {
        $errors = collect([]);
        // dd($this->ethocaUpdates->errors);
        $errors = $errors->concat($this->ethocaResponse->errors);
        $errors = $errors->concat($this->ethocaAcknowledgements()->get()->map(function ($ack) {
            return $ack->errors;
        })->collapse());
        $errors = $errors->concat($this->ethocaUpdates()->get()->map(function ($ack) {
            return $ack->errors;
        })->collapse());
        $errors = $errors->concat(
            EthocaError::where(
                [
                    'model' => self::class,
                    'model_id' => $this->id
                ]
            )->get()
        );
        // dd($errors);
        return Attribute::make(
            get: function () use ($errors) {
                return $errors;
            }
        );
    }
    public function getAlertsAttribute()
    {
        return $this->ethocaResponse->ethocaAlerts;
    }

    public function ethocaAcknowledgements(): HasMany
    {
        return $this->hasMany(EthocaAcknowledgement::class);
    }

    public function ethocaUpdates(): HasMany
    {
        return $this->hasMany(EthocaUpdate::class);
    }

    protected static function newFactory(): Factory
    {
        return RequestFactory::new();
    }

    public static function generateRequest(\SoapClient $client, $ethoc_function, $ethoca_args, $start = null, $end = null): mixed
    {
        $title = 'Ethoca 360 Alerts Request';
        $alert_type = null;
        switch ($ethoc_function) {
            case 'Ethoca360Alerts':
                $title = 'Ethoca 360 Alerts Request';
                $alert_type = $ethoca_args['AlertType'] ?? 'all'; // default to 'all
                $ethoca_fun_code = 1;
                break;
            case 'EthocaAlertAcknowledgement':
                $title = 'Acknowledge Ethoca Alerts Request';
                $ethoca_fun_code = 2;
                break;
            case 'Ethoca360AlertsUpdate':
                $title = 'Update Ethoca Alerts Request';
                $ethoca_fun_code = 3;
                break;
        }
        $ethoca_request = EthocaRequest::create([
            'title' => $title,
            'alert_type' => $alert_type, // default to 'all
            'ethoca_fun_code' => $ethoca_fun_code,
            'search_start_date' => $ethoca_args['SearchStartDate'] ?? null,
            'search_end_date' => $ethoca_args['SearchEndDate'] ?? null,
        ]);
        try {
            $response = $client->__soapCall($ethoc_function, $ethoca_args, ["trace" => true, "_connection_timeout" => 180]);
            $alert_res_model = EthocaResponse::create([
                'major_code' => $response->majorCode,
                'status' => $response->Status,
                'number_of_alerts' => $response->numberOfAlerts ?? null,
                'ethoca_request_id' => $ethoca_request->id ?? null,
            ]);
            if (isset($response->Errors) && is_array($response->Errors->Error)) {
                foreach ($response->Errors->Error as $error) {
                    EthocaError::create([
                        'model' => EthocaResponse::class,
                        'model_id' => $alert_res_model->id,
                        'code' => $error->code,
                        'description' => $error->_,
                    ]);
                }
            } else {
                EthocaError::create([
                    'model' => EthocaResponse::class,
                    'model_id' => $alert_res_model->id,
                    'code' => $response->Errors->Error->code,
                    'description' => $response->Errors->Error->_,
                ]);
            }
            $response->model = $alert_res_model;
        } catch (\Throwable $th) {
            EthocaError::create([
                'model' => EthocaRequest::class,
                'model_id' => $ethoca_request->id,
                'code' => 500,
                'description' => 'Failed to connect to Ethoca 360 Alerts API',
            ]);
            $response = (object) [
                'Status' => 'Failed',
                'Errors' => (object) [
                    'Error' => (object) [
                        'code' => 500,
                        '_' => 'Failed to connect to Ethoca 360 Alerts API',
                    ]
                ]
            ];
            return $response;
        }
        // save all errors to database


        return $response;
    }
    // public static function generateRequest(\SoapClient $client, $ethoc_function, $ethoca_args, $start = null, $end = null): mixed
    // {
    //     $title = 'Ethoca 360 Alerts Request';
    //     $alert_type = null;
    //     switch ($ethoc_function) {
    //         case 'Ethoca360Alerts':
    //             $title = 'Ethoca 360 Alerts Request';
    //             $alert_type = $ethoca_args['AlertType'] ?? 'all'; // default to 'all
    //             break;
    //         case 'EthocaAlertAcknowledgement':
    //             $title = 'Acknowledge Ethoca Alerts Request';
    //             break;
    //         case 'Ethoca360AlertsUpdate':
    //             $title = 'Update Ethoca Alerts Request';
    //             break;
    //     }
    //     $ethoca_request = EthocaRequest::create([
    //         'title' => $title,
    //         'alert_type' => $alert_type, // default to 'all
    //         'search_start_date' => $ethoca_args['SearchStartDate'] ?? null,
    //         'search_end_date' => $ethoca_args['SearchEndDate'] ?? null,
    //     ]);
    //     $response = $client->__soapCall($ethoc_function, $ethoca_args, ["trace" => true, "_connection_timeout" => 180]);
    //     // save all errors to database
    //     if (isset($response->Errors) && isset($response->Errors->Error)) {
    //         foreach ($response->Errors->Error as $error) {
    //             EthocaError::create([
    //                 'model' => EthocaRequest::class,
    //                 'model_id' => $ethoca_request->id,
    //                 'code' => $error->code,
    //                 'description' => $error->_,
    //             ]);
    //         }
    //     }
    //     $response['ethoca_request_id'] = $ethoca_request->id;
    //     return $response;
    // }
}
