<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EthocaError extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function error_origin(): BelongsTo
    {
        return $this->belongsTo($this->model, $this->model_id);
    }

    public static function generateErrorsFromResponse($response, $model)
    {
        $errors = [];
        if (isset($response->Errors) && isset($response->Errors->Error)) {
            foreach ($response->Errors->Error as $error) {

                $error_record = EthocaError::create([
                    'model' => get_class($model),
                    'model_id' => $model->id,
                    'code' => $error->code,
                    'description' => $error->_,
                ]);
                array_push($errors, $error_record);
            }
        }
        return $errors;
    }
}
