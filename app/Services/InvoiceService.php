<?php


namespace App\Services;


use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Invoice;
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
        $validated_data=$request->validated();
        $validated_data['created_by']= auth()->user()->id;
        // dd($validated_data);

        return $this->InvoiceRepository->create($validated_data);
    }
    /************************************************/
    public function get_client($id)
    {
        return $this->InvoiceRepository->getById($id);
    }
    /************************************************/
    public function update($request,$id)
    {
        $validated_data=$request->validated();
        $validated_data['updated_by'] = auth()->user()->id;
        // dd($validated_data);

        return $this->InvoiceRepository->update($id,$validated_data);
    }
    /**************************************************/




}
