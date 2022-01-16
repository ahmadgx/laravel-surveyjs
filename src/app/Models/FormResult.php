<?php

namespace AidynMakhataev\LaravelSurveyJs\app\Models;

use Illuminate\Database\Eloquent\Model;

class FormResult extends Model
{
    protected $table = 'form_results';
    protected $fillable = [
        'form_id', 'user_id', 'ip_address', 'json',
    ];
    protected $casts = [
        'json'  =>  'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo('AidynMakhataev\LaravelSurveyJs\app\Models\Form', 'form_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('form-manager.user_model'), 'user_id');
    }
}
