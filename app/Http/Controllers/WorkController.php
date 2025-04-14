<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Experience;
use App\Models\Salary;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $works = Work::all();

        $filteredWorks = [];

        foreach ($works as $work) {
            $category = Category::find($work->category_id)->category_name;
            $salary = Salary::find($work->salary_id);
            $experience = Experience::find($work->experience_id)->experience;

            $filteredWorks[] = [
                'id' => $work->id,
                'company_name' => $work->company_name,
                'company_address' => $work->company_address,
                'job_description' => $work->job_description,
                'experience' => $experience,
                'category' => $category,
                'min_salary' => $salary->min,
                'max_salary' => $salary->max,
                'hrd_email' => $work->hrd_email,
                'contact' => $work->contact
            ];
        }

        return response($filteredWorks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Work $work)
    {
        $category = Category::find($work->category_id)->category_name;
        $salary = Salary::find($work->salary_id);
        $experience = Experience::find($work->experience_id)->experience;

        return response([
            'id' => $work->id,
            'company_name' => $work->company_name,
            'company_address' => $work->company_address,
            'job_description' => $work->job_description,
            'experience' => $experience,
            'category' => $category,
            'min_salary' => $salary->min,
            'max_salary' => $salary->max,
            'hrd_email' => $work->hrd_email,
            'contact' => $work->contact
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Work $work)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Work $work)
    {
        //
    }

    public function salaries()
    {
        $salaries = Salary::all();

        return response($salaries);
    }

    public function experiences()
    {
        $experiences = Experience::all();

        return response($experiences);
    }
}
