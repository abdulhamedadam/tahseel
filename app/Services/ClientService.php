<?php


namespace App\Services;


use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin;
use App\Models\Admin\Invoice;
use App\Models\Admin\Subscription;
use App\Models\Clients;
use App\Notifications\NewClientAddedNotification;
use App\Traits\ImageProcessing;

class ClientService
{

    use ImageProcessing;
    protected $ClientsRepository;
    protected $InvoiceRepository;
    public function __construct(BasicRepositoryInterface $basicRepository)
    {
        $this->ClientsRepository   = createRepository($basicRepository, new Clients());
        $this->InvoiceRepository   = createRepository($basicRepository, new Invoice());
    }
    /************************************************/
    public function store($request)
    {
        $validated_data=$request->validated();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $dataX = $this->saveImage($file, 'clients');
            $validated_data['image'] = $dataX;
        }
        $validated_data['created_by']= auth()->user()->id;
        // dd($validated_data);

        $client = $this->ClientsRepository->create($validated_data);

        // $invoiceNumber = $this->InvoiceRepository->getLastFieldValue('invoice_number');

        // $invoice_data = [
        //     'invoice_number' => $invoiceNumber,
        //     'client_id' => $client->id,
        //     'subscription_id' => $validated_data['subscription_id'],
        //     'amount' => $validated_data['price'],
        //     'remaining_amount' => $validated_data['price'],
        //     'enshaa_date' => now(),
        //     'due_date' => now()->addMonth(),
        //     'status' => 'unpaid',
        // ];

        // $this->InvoiceRepository->create($invoice_data);

        $admins = Admin::where('status', '1')
                    ->whereNull('deleted_at')
                    ->where('id', '!=', auth()->id())
                    ->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewClientAddedNotification($client));
        }

        return $client;
    }
    /************************************************/
    public function get_client($id)
    {
        return $this->ClientsRepository->getById($id);
    }
    /************************************************/
    public function update($request,$id)
    {
        $validated_data=$request->validated();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $dataX = $this->saveImage($file, 'clients');
            $validated_data['image'] = $dataX;
        }
        $validated_data['updated_by'] = auth()->user()->id;
        //dd($validated_data);
        return $this->ClientsRepository->update($id,$validated_data);
    }
    /**************************************************/




}
