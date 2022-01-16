<?php

namespace AidynMakhataev\LaravelSurveyJs\app\Http\Controllers\API;

use AidynMakhataev\LaravelSurveyJs\app\Models\Branch;
use Illuminate\Routing\Controller;

class APIController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function getBranches()
    {
        $data = Branch::get();
        return response()->json($data);
    }
}