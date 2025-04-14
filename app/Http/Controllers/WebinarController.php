<?php

namespace App\Http\Controllers;

use App\Models\Webinar;
use Illuminate\Http\Request;

class WebinarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $webinars = Webinar::all();

        $filteredWebinars = [];

        foreach ($webinars as $webinar) {
            $filteredWebinars[] = [
                'id' => $webinar->id,
                'title' => $webinar->title,
                'start_date' => $webinar->start_date,
                'end_date' => $webinar->end_date,
                'webinar_link' => $webinar->webinar_link,
                'speakers' => $webinar->speakers
            ];
        }

        return response($filteredWebinars);
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
    public function show(Webinar $webinar)
    {
        return response([
            'id' => $webinar->id,
            'title' => $webinar->title,
            'start_date' => $webinar->start_date,
            'end_date' => $webinar->end_date,
            'webinar_link' => $webinar->webinar_link,
            'speakers' => $webinar->speakers
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Webinar $webinar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Webinar $webinar)
    {
        //
    }
}
