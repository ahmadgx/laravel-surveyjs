<?php

namespace AidynMakhataev\LaravelSurveyJs\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'  =>  'required|max:255',
            'branch_id'  =>  'required|numeric|exists:branches,id',
        ];
    }
}
