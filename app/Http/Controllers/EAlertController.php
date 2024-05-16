<?php

namespace App\Http\Controllers;

use App\Models\EthocaAlert;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class EAlertController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function index()
    {
        return view('alerts.index', ['requests' => EthocaAlert::all()]);
    }
    function show($id)
    {
        return view('alerts.show', ['request' => EthocaAlert::find($id)]);
    }
}
