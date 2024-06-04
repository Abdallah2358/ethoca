<?php

namespace App\Http\Controllers;

use App\Models\Error;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller as BaseController;

class ErrorController extends BaseController
{
    public function index()
    {
        return view('errors.index');
    }
    public function data()
    {
        return DataTables::of(Error::query())->toJson();
    }

}
