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
    function company(Company $company)
    {
        return view('alerts.companies.show', ['company' => $company]);
    }
    function companyData(Company $company)
    {
        $merchants = $company->alerts();
        return DataTables::of($merchants)->toJson();
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
        $merchants = Merchant::query();
        return DataTables::of($merchants)->toJson();
    }
    function merchantData(Merchant $merchant)
    {
        $alertsQuery = $merchant->alerts()->getQuery();
        return DataTables::of($alertsQuery)->toJson();
    }
}
