<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CronJob;

class CronJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return "hello index";
        return response()->json(CronJob::all(), 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return "hello store";
        $validatedData = $request->validate([
            'name' => 'required',
            'url' => 'required|url',
            'interval' => 'required|numeric', // Interval in minutes
        ]);

        // return $request;

        $cronJob = CronJob::create($validatedData);

        return response()->json($cronJob, 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cronJob = CronJob::findOrFail($id);
        $cronJob->delete();
        return response()->json(null, 204);
    }
}
