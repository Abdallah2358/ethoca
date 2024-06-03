<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EthocaAlert;
use App\Models\Merchant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;

class EAlertController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function index()
    {
        // dd(EthocaAlert::all());
        return view('alerts.index');
    }
    function data(): mixed
    {
        $model = EthocaAlert::query();
        return DataTables::of($model)->toJson();
    }
    function show($id)
    {
        return view('alerts.show', ['alert' => EthocaAlert::findOrFail($id)]);
    }

    function companies()
    {
        return view('alerts.companies.index');
    }

    function companiesData()
    {
        $companies = Company::all();
        return DataTables::of($companies)->toJson();
    }
    function company(Company $company)
    {
        return view('alerts.companies.show', ['company' => $company]);
    }
    function companyData(Company $company)
    {
        // $companies = Company::all('id', 'name');
        $merchants = $company->merchants()->getQuery();
        return DataTables::of($merchants)->addColumn('company_name', function ($merchant) use ($company) {
            return $company->name;
        })->toJson();
    }

    function merchants()
    {
        return view('alerts.merchants.index');
    }
    function merchant(Merchant $merchant)
    {
        return view('alerts.merchants.show', ['merchant' => $merchant]);
    }

    function merchantsData()
    {
        $companies = Company::all('id', 'name');
        $merchants = Merchant::query();
        return DataTables::of($merchants)->addColumn('company_name', function ($merchant) use ($companies) {
            return $companies->where('id', $merchant->company_id)->first()->name;
        })->toJson();
    }
    function merchantData(Merchant $merchant)
    {
        $alertsQuery = $merchant->alerts()->getQuery();
        return DataTables::of($alertsQuery)->toJson();
    }
}
