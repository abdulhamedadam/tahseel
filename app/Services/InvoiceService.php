<?php


namespace App\Services;


use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Invoice;
use App\Models\Admin\Revenue;
use App\Models\Admin\Subscription;
use App\Models\Clients;
use App\Traits\ImageProcessing;

class InvoiceService
{

    use ImageProcessing;
    protected $InvoiceRepository;
    public function __construct(BasicRepositoryInterface $basicRepository)
    {
        $this->InvoiceRepository   = createRepository($basicRepository, new Invoice());
    }
    /************************************************/
    public function store($request)
    {
        $validated_data = $request->validated();
        $validated_data['created_by'] = auth()->user()->id;
        // dd($validated_data);

        return $this->InvoiceRepository->create($validated_data);
    }
    /************************************************/
    public function get_client($id)
    {
        return $this->InvoiceRepository->getById($id);
    }
    /************************************************/
    public function update($request, $id)
    {
        $validated_data = $request->validated();
        $validated_data['updated_by'] = auth()->user()->id;
        // dd($validated_data);

        return $this->InvoiceRepository->update($id, $validated_data);
    }
    /**************************************************/
    public function payInvoice($id, $request)
    {
        $invoice = $this->InvoiceRepository->getById($id);
        $invoice->amount = $request->invoice_amount;
        // dd($invoice);

        $totalPaid = $invoice->paid_amount + $request->paid_amount;
        $remainingAmount = $invoice->amount - $totalPaid;
        // dd($invoice, $remainingAmount, $totalPaid);
        if ($totalPaid > $invoice->amount) {
            return redirect()->back()->with('error', trans('invoices.payment_exceeds_invoice_amount'));
        }

        $invoice->paid_amount = $totalPaid;
        $invoice->remaining_amount = $remainingAmount;

        if ($remainingAmount == 0) {
            $invoice->status = 'paid';
        } elseif ($totalPaid > 0) {
            $invoice->status = 'partial';
        } else {
            $invoice->status = 'unpaid';
        }

        $invoice->paid_date = now()->format('Y-m-d');
        $invoice->notes = $request->notes ?? null;
        $invoice->save();

        $collectedBy = auth()->check() && auth()->user()->is_employee
            ? auth()->user()->emp_id
            : auth()->id();

        Revenue::create([
            'invoice_id' => $invoice->id,
            'client_id' => $invoice->client_id,
            'amount' => $request->paid_amount,
            'collected_by' => $collectedBy,
            'received_at' => now(),
        ]);

        return $invoice;
    }
}
