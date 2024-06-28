<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::latest()->with(['employer', 'tags'])->get()->groupBy('featured');
        return view('job.index', [
            'featured_job'=>$jobs[1],
            'jobs'=>$jobs[0],
            'tags'=>Tag::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('job.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attr = $request->validate([
            'title' => ['required'],
            'salary' => ['required'],
            'location' => ['required'],
            'schedule' => ['required', Rule::in(['Part Time', 'Full Time'])],
            'url' => ['required', 'active_url'],
            'tags' => ['nullable'],
            'discription' => ['required'],
        ]);
        $attr['featured']=$request->has('featured');
        $job = Auth::user()->employer->job()->create(Arr::except($attr, 'tags'));
        $strtags = $request->input('tags',[]);
        $tags = explode(' ',$strtags);
        $tagid = [];
        foreach($tags as $tag){
            $tag = Tag::firstOrCreate(['name' => trim($tag)]);
            $tagid [] = $tag->id; 
        };
        $job->tags()->sync($tagid);
        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return view('job.show', ['job'=>$job]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        return view('job.edit', ['job'=>$job]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $attr = $request->validate([
            'title' => ['required'],
            'salary' => ['required'],
            'location' => ['required'],
            'schedule' => ['required', Rule::in(['Part Time', 'Full Time'])],
            'url' => ['required', 'active_url'],
            'tags' => ['nullable'],
            'discription' => ['required'],
        ]);
        $attr['featured']=$request->has('featured');
        $job->update(Arr::except($attr, 'tags'));
        $strtags = $request->input('tags');
        
        $tags = explode(' ',$strtags);
        $tagid = [];
        foreach($tags as $tag){
            $tag = Tag::firstOrCreate(['name' => trim($tag)]);
            $tagid [] = $tag->id; 
        }
        $job->tags()->sync($tagid);
        return redirect('/jobs/'.$job->id);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return redirect('/');
    }
}
