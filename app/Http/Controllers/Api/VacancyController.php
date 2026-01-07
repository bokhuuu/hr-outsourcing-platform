<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vacancy\StoreVacancyRequest;
use App\Models\Company;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Vacancy::where('status', 'published')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVacancyRequest $request, Company $company)
    {
        $vacancy = Vacancy::create([
            'company_id' => $company->id,
            'created_by' => auth()->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'employment_type' => $request->employment_type,
            'status' => $request->status ?? 'draft',
            'published_at' => $request->status === 'published' ? now() : null,
            'expiration_date' => $request->expiration_date,
        ]);

        return response()->json($vacancy, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacancy $vacancy)
    {
        if ($vacancy->status !== 'published') {
            abort(404);
        }

        return $vacancy;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
