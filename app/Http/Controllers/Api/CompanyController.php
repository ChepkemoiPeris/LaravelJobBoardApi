<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Http\Resources\CompanyResource; 
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
        return CompanyResource::collection($companies);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $company = Company::create($request->validated());

        return response()->json([
            'message' => 'Company created successfully',
            'data' => new CompanyResource($company)
        ], 201);
    }

    /**
     * Display the specified company.
     */
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

   
    /**
     * Update the specified resource company.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update($request->validated());

        return response()->json([
            'message' => 'Company updated successfully',
            'data' => new CompanyResource($company)
        ]);
    }

 

    /**
     * Delete company.
     */
    public function destroy(Company $company)
    {
        
        $company->delete();

        return response()->json(['message' => 'Company deleted successfully']);
    }
 

 
}
