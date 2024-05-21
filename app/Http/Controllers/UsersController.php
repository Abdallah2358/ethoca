<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return view('users.index2');
    }
    public function data()
    {
        $model = User::query();
        return DataTables::of($model)->toJson();
    }

}
