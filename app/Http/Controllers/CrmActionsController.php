<?php

namespace App\Http\Controllers;

use App\Models\CrmAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Yajra\DataTables\DataTables;

class CrmActionsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function index()
    {
        // dd(CrmAction::all());
        return view('crm-actions.index', ['actions' => CrmAction::paginate()]);
    }
    public function data(){
        return DataTables::of(CrmAction::query())->toJson();
    }
    function show($id)
    {
        return view('crm-actions.show', ['action' => CrmAction::findOrFail($id)]);
    }
}
