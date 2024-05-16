<?php

namespace App\Http\Controllers;

use App\Models\EthocaRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class ERequestController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function index()
    {
        return view('requests.index', ['requests' => EthocaRequest::all()]);
    }
    function show($id)
    {
        return view('requests.show');
    }
}
