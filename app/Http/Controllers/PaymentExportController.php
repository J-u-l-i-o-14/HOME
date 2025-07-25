<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;

class PaymentExportController extends Controller
{
    // Middleware pour admin/manager
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,manager']);
    }

    // Export CSV
    public function exportCsv(Request $request)
    {
        return Excel::download(new PaymentsExport, 'transactions.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    // Export XLSX
    public function exportXlsx(Request $request)
    {
        return Excel::download(new PaymentsExport, 'transactions.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
