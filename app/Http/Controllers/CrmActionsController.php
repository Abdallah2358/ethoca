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
        return view('crm-actions.index', ['crmActions' => CrmAction::all()]);
    }
    function show($id)
    {
        return view('crm-actions.show', ['crmAction' => CrmAction::findOrFail($id)]);
    }
}
