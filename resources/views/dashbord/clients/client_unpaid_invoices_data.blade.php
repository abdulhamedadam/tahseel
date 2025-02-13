<div class="" style="margin-top: 30px">
    @if(isset($unpaid_data) && !empty($unpaid_data))
        <table id="table" class="example table table-bordered responsive nowrap text-center" cellspacing="0"
               width="70%">
            <thead>
            <tr class="greentd" style="background-color: lightgrey" >
                <th>{{trans('invoices.hash') }}</th>
                <th>{{ trans('invoices.invoice_number') }}</th>
                <th>{{ trans('invoices.subscription') }}</th>
                <th>{{ trans('invoices.amount') }}</th>
                <th>{{ trans('invoices.enshaa_date') }}</th>
                <th>{{ trans('invoices.due_date') }}</th>
                <th>{{ trans('invoices.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @php
                $x = 1;
            @endphp
            @foreach ($unpaid_data as $invoice)
                <tr>
                    <td>{{ $x++ }}</td>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->subscription ? $invoice->subscription->name : 'خدمة' }}</td>
                    <td>{{ $invoice->amount }}</td>
                    <td class="fnt_center_black">{{ $invoice->paid_date ? \Illuminate\Support\Carbon::parse($invoice->paid_date)->format('Y-m-d h:i A') : 'N\A'}}</td>
                    <td class="fnt_center_black">{{ $invoice->enshaa_date ? \Illuminate\Support\Carbon::parse($invoice->enshaa_date)->format('Y-m-d') : 'N\A'}}</td>
                    <td class="fnt_center_black">{{ $invoice->due_date ? \Illuminate\Support\Carbon::parse($invoice->due_date)->format('Y-m-d') : 'N\A'}}</td>
                    <td>
                        <div class="btn-group">
                            {{-- <a href="{{ route('admin.employee_delete_invoiceat', $invoice->id) }}" onclick="return confirm('Are You Sure To Delete?')" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a> --}}
                            <a href="javascript:void(0)" onclick="invoice_details('{{ route('admin.invoice_details', $invoice->id) }}')"
                                class="text-primary fw-bold text-decoration-underline" title="{{ trans('invoices.view_details') }}">
                                INV-{{ $invoice->invoice_number }}
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>

@section('js')
<script>
    function invoice_details(url) {
        $.get(url, function(data) {
            $('#result_info').html(data);
            $('#modaldetails').modal('show');
        });
    }
</script>
@endsection





