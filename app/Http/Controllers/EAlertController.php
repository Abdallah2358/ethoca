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
        return view('alerts.index', ['alerts' => EthocaAlert::paginate(200)]);
    }
    function show($id)
    {
        return view('alerts.show', ['alert' => EthocaAlert::findOrFail($id)]);
    }
}
