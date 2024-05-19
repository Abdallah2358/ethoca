<?php

namespace App\Http\Controllers;

use App\Models\CrmAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class CrmActionsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function index()
    {
        return view('crm-actions.index', ['actions' => CrmAction::paginate()]);
    }
    function show($id)
    {
        return view('crm-actions.show', ['action' => CrmAction::findOrFail($id)]);
    }
}
