<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCompanyRequest;
use App\Http\Requests\Api\UpdateCompanyRequest;
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
        $companies = Company::paginate(20);
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
    public function show($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'message' => 'Company not found'
            ], 404);
        }

        return new CompanyResource($company); 
    }

   
    /**
     * Update the specified resource company.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        if (auth()->user()->role !== 'company_user' ||  auth()->user()->company_id !== $company->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
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
        if (auth()->user()->role !== 'company_user' ||  auth()->user()->company_id !== $company->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $company->delete();

        return response()->json(['message' => 'Company deleted successfully']);
    }
 

 
}
