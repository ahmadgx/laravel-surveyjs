<?php

namespace AidynMakhataev\LaravelSurveyJs\app\Http\Controllers\API;

use AidynMakhataev\LaravelSurveyJs\app\Http\Requests\CreateSurveyRequest;
use AidynMakhataev\LaravelSurveyJs\app\Http\Requests\UpdateSurveyRequest;
use AidynMakhataev\LaravelSurveyJs\app\Http\Resources\SurveyResource;
use AidynMakhataev\LaravelSurveyJs\app\Models\Form;
use Illuminate\Routing\Controller;

class SurveyAPIController extends Controller
{
    public function index()
    {
        $surveys = Form::latest()->paginate(config('form-manager.pagination_perPage', 10));

        return SurveyResource::collection($surveys);
    }

    public function show($id)
    {
        $survey = Form::find($id);

        if (is_null($survey)) {
            return response()->json('Form not found', 404);
        }

        return response()->json([
            'data'      =>  new SurveyResource($survey),
            'message'   =>  'Form successfully retrieved',
        ]);
    }

    public function store(CreateSurveyRequest $request)
    {
        $survey = Form::create($request->all());

        return response()->json([
            'data'      =>  new SurveyResource($survey),
            'message'   =>  'Form saved successfully',
        ], 201);
    }

    public function update($id, UpdateSurveyRequest $request)
    {
        $survey = Form::find($id);

        if (is_null($survey)) {
            return response()->json('Form not found', 404);
        }

        $survey->update($request->all());

        return response()->json([
            'data'      =>  new SurveyResource($survey),
            'message'   =>  'Form successfully updated',
        ]);
    }

    public function destroy($id)
    {
        $survey = Form::find($id);

        if (is_null($survey)) {
            return response()->json('Form not found', 404);
        }
        $survey->delete();

        return response()->json([
            'data' => $id,
            'message' => 'Form deleted successfully',
        ], 200);
    }
}
