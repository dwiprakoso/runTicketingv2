<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export orders to Excel
     *
     * @param string|null $status
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportOrders($status = null)
    {
        return Excel::download(new OrdersExport($status), 'data_pemesan.xlsx');
    }
}