<?php

namespace App\Http\Controllers;

use App\Models\CrmTransaction;
use Illuminate\Http\Request;

class CrmTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        switch ($request->paid) {
            case '1':
                $crmTransactions = CrmTransaction::where('isChargedback', true)->paginate(100);
                break;
            case '0':
                $crmTransactions = CrmTransaction::where('isChargedback', 0)->paginate(100);
                break;
            default:
                $crmTransactions = CrmTransaction::paginate(250);
                break;
        }

        // $crmTransactions= CrmTransaction::where('isChargedback',true)->paginate(100);
        return view('crm-transactions.index', compact('crmTransactions'));
    }

    /**
     * Display the specified resource.
     */
    public function show(CrmTransaction $crmTransaction)
    {
        //
        return view('crm-transactions.show', compact('crmTransaction'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CrmTransaction $crmTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CrmTransaction $crmTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CrmTransaction $crmTransaction)
    {
        //
    }
}
