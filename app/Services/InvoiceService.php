<?php
namespace App\Services;

use App\Models\Invoice;
use App\Traits\InvoiceHandler;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    use InvoiceHandler;
    public function InvoicePaginate()
    {
        $paginate_number = config('app.paginate_number', 10);

        // Try this first to test:
        return Invoice::query()
            ->select([
                'id',
                'invoice_number',
                'customer_id', 
                'user_id',    
                'sub_total',
                'discount',
                'total',
                'invoice_date',
            ])
            ->with([
                'customer:id,name',
                'employee:id,name',
            ])
            ->latest()
            ->paginate($paginate_number);
    }

    public function createInvoice(array $data): Invoice
    {
        $invoice = DB::transaction(function () use ($data) {
            $invoiceData = [
                'invoice_number' => $data['invoice_number'],
                'customer_id'    => $data['customer_id'],
                'user_id'        => auth()->id(),
                'invoice_date'   => $data['invoice_date'],
            ];
            $invoice = Invoice::create($invoiceData);

            $totalsAndProducts = $this->prepareInvoiceData($data['products'], $data['discount'] ?? 0);

            $invoice->products()->sync($totalsAndProducts['products']);

            $invoice->update([
                'sub_total' => $totalsAndProducts['sub_total'],
                'discount'  => $totalsAndProducts['discount'],
                'total'     => $totalsAndProducts['total'],
            ]);
            return $invoice;
        });

        return $invoice;
    }

    public function getInvoiceById(int $id, string | array $relations = []): Invoice
    {
        return Invoice::with($relations)->findOrFail($id);
    }

    public function dailyIncome()
    {
        $paginate_number = config('app.paginate_number', 10);

       return Invoice::whereDate('invoice_date', today())
            ->select('invoice_number', 'customer_id', 'user_id', 'sub_total', 'discount', 'total', 'invoice_date')
            ->withSum('products as items_count', 'products_invoices.quantity')
            ->paginate($paginate_number);
    }
    public function monthlyIncome()
    {
        $paginate_number = config('app.paginate_number', 10);

      return  Invoice::whereDate('invoice_date', '>=', \Carbon\Carbon::now()->startOfMonth())
            ->whereDate('invoice_date', '<=', \Carbon\Carbon::now()->endOfMonth())
            ->select('invoice_number', 'customer_id', 'user_id', 'sub_total', 'discount', 'total', 'invoice_date')
            ->withSum('products as items_count', 'products_invoices.quantity')
            ->paginate($paginate_number);
    }

    public function getAllCustomers()
    {
        return \App\Models\Customer::select('id', 'name')->get();
    }
    public function getAllProducts()
    {
        return \App\Models\Product::select('id', 'name', 'price')->get();
    }

    public function generateNextInvoiceNumber(): string
    {
        return 'INV-'.date('YmdHisu');

    }

}
