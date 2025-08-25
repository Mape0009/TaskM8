<?php

namespace App\Http\Controllers;

use App\Models\Job;

use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::all();

        return response()->json($jobs);
    }

    public function create(Request $request)
    {
        $job = new Job();
        $job->jobName = $request->input('jobName');
        $job->save();

        return response()->json(['message' => 'Job created successfully', 'job' => $job]);
    }

    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $job->jobName = $request->input('jobName');
        $job->save();

        return response()->json($job);
    }

    public function edit($id)
    {
        $job = Job::findOrFail($id);

        return view('edit.job', compact('job'));
    }

    public function delete($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return response()->json(null, 204);
    }
}
