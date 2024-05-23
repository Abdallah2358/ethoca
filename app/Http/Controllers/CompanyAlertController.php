<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CompanyAlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::query()->get()->toArray();
        // dd($companies[0]);
        return view('companies.alerts.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('companies.alerts.show');
    }
    public function data(Request $request)
    {
        $companies = Company::query();
        return DataTables::of($companies)->toJson();
    }
}
