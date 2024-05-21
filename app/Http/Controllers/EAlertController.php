<?php

namespace App\Http\Controllers;

use App\Models\EthocaAlert;
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
}
