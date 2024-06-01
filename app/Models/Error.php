<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Casts\Json;
use Database\Factories\ErrorFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Error extends Model
{
    use HasFactory;
    protected $guarded = [];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'exception' => Json::class,
        ];
    }
    /**
     * Interact with the user's first name.
     */
    protected function exception(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value),
            set: fn($value) => json_encode($value),
        );
    }
    protected static function newFactory(): Factory
    {
        return ErrorFactory::new();
    }
    public function error_origin(): BelongsTo
    {
        return $this->belongsTo($this->model, $this->model_id);
    }

    public function ethocaResponse(): BelongsTo
    {
        return $this->belongsTo(EthocaResponse::class, 'model_id');
    }
    public function ethocaRequest(): BelongsTo
    {
        return $this->belongsTo(EthocaRequest::class, 'model_id');
    }
    public function ethocaAlert(): BelongsTo
    {
        return $this->belongsTo(EthocaAlert::class, 'model_id');
    }
    public function ethocaAcknowledgement(): BelongsTo
    {
        return $this->belongsTo(EthocaAcknowledgement::class, 'model_id');
    }
    public function ethocaUpdate(): BelongsTo
    {
        return $this->belongsTo(EthocaUpdate::class, 'model_id');
    }
    public function crmAction(): BelongsTo
    {
        return $this->belongsTo(CrmAction::class, 'model_id');
    }

    public function crmTransaction(): BelongsTo
    {
        return $this->belongsTo(CrmTransaction::class, 'model_id');
    }
    public static function generateErrorsFromResponse($response, $model)
    {
        $errors = [];
        if (isset($response->Errors) && is_array($response->Errors->Error)) {
            foreach ($response->Errors->Error as $error) {

                $error_record = Error::create([
                    'model' => get_class($model),
                    'model_id' => $model->id,
                    'code' => $error->code,
                    'description' => $error->_,
                ]);
                array_push($errors, $error_record);
            }
        } else {
            $error_record = Error::create([
                'model' => get_class($model),
                'model_id' => $model->id,
                'code' => $response->Errors->Error->code,
                'description' => $response->Errors->Error->_,
            ]);
            array_push($errors, $error_record);
        }
        return $errors;
    }
}
