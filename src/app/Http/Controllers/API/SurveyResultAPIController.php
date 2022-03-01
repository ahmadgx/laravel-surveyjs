<?php

namespace AidynMakhataev\LaravelSurveyJs\app\Http\Controllers\API;

use AidynMakhataev\LaravelSurveyJs\app\Http\Resources\SurveyResource;
use AidynMakhataev\LaravelSurveyJs\app\Http\Resources\SurveyResultResource;
use AidynMakhataev\LaravelSurveyJs\app\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SurveyResultAPIController extends Controller
{
    public function index(Form $form)
    {
        $results = $form->results()->paginate(config('form-manager.pagination_perPage', 10));

        return SurveyResultResource::collection($results)
                ->additional(['meta' => [
                    'survey'    =>  new SurveyResource($form),
                ]]);
    }

    /**
     * @param Form $survey
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Form $form, Request $request)
    {
        $request->validate([
            'json'  =>  'required',
        ]);

        $result = $form->results()->create([
            'json'          =>  $request->input('json'),
            'email'          => $request->input('json')['Email'],
            'user_id'       =>  \Auth::check() ? \Auth::id() : null,
            'ip_address'    =>  $request->ip(),
        ]);

        return response()->json([
            'data'      =>  new SurveyResultResource($result),
            'message'   =>  'Form Result successfully created',
        ], 201);
    }

    public function uploadFile(Request $request){
        $request->validate([
            'file' => 'required|mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf|max:2048'
        ]);

        if($request->file()) {
            $name = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads/user_profile', $name, 'public');
            $name = '/storage/' . $filePath;

            return response()->json([
                'data'      =>  $name,
                'message'   =>  'File successfully saved',
            ], 201);
        }
    }


    public function backEndValidation(Request $request){

    }
}
