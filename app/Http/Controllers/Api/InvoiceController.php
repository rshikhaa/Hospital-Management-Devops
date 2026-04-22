<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController
{
    public function index(Request $request)
    {
        $invoices = Invoice::where('patient_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // include download URL
        $invoices->getCollection()->each->append('file_url');
        return response()->json(['success' => true, 'data' => $invoices]);
    }

    public function show(Request $request, Invoice $invoice)
    {
        if ($invoice->patient_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $invoice->append('file_url');
        return response()->json(['success' => true, 'data' => $invoice]);
    }
}
