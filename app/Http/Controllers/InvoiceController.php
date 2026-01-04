<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class InvoiceController extends Controller
{
    use AuthorizesRequests; 

    public InvoiceService $invoiceService;
    public function __construct( InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }
    public function index()
    {
        $this->authorize('view_all_invoices');
        $data['invoices'] = $this->invoiceService->invoicePaginate();
        return view('invoices.index', $data);
    }

    public function create()
    {
        $this->authorize('create_invoices');
        $data['customers'] = $this->invoiceService->getAllCustomers();
        $data['products'] = $this->invoiceService->getAllProducts();
        $data['nextInvoiceNumber'] = $this->invoiceService->generateNextInvoiceNumber();
        return view('invoices.create' , $data);
    }

    public function store(InvoiceRequest $request)
    {
        $this->authorize('create_invoices');
        $data = $request->validated();

        $this->invoiceService->createInvoice($data);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }   


    public function show($id)
    {
        $this->authorize('view_invoices');
        $data['invoice'] = $this->invoiceService->getInvoiceById($id, ['customer', 'employee', 'products']);
          $this->authorize('view',  $data['invoice']);
        return view('invoices.show', $data);
    }

    public function downloadPDF( $id)
    {
        $this->authorize('print_invoices');
        $invoice = $this->invoiceService->getInvoiceById($id ,['customer', 'employee', 'products']);
       
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    public function dailyReport()
    {
        $this->authorize('report_invoices');
        $data['invoices'] = $this->invoiceService->dailyIncome();
        return view('invoices.daily_report', $data);
    }
    public function monthlyReport()
    {
        $this->authorize('report_invoices');
        $data['invoices'] = $this->invoiceService->monthlyIncome();
        return view('invoices.daily_report', $data);
    }



}
