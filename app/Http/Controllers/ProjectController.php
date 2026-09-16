<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $ongoingProjects = Project::ongoing()
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $completedProjects = Project::completed()
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $offPlanProjects = Project::offPlan()
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('projects.index', compact('ongoingProjects', 'completedProjects', 'offPlanProjects'));
    }

    public function ongoing()
    {
        $projects = Project::ongoing()
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('projects.ongoing', compact('projects'));
    }

    public function completed()
    {
        $projects = Project::completed()
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('projects.completed', compact('projects'));
    }

    public function offPlan()
    {
        $projects = Project::offPlan()
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('projects.off-plan', compact('projects'));
    }

    public function show($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        $similarProjects = Project::where('id', '!=', $project->id)
            ->where('city', $project->city)
            ->take(3)
            ->get();

        return view('projects.show', compact('project', 'similarProjects'));
    }
}