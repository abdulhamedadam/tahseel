<form method="post" action="{{ route('admin.client_add_invoice',$all_data->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="row col-md-12 ">
        <div class="col-md-4">
            <label for="invoice_number" class="form-label">{{ trans('clients.invoice_number') }}</label>
            <div class="input-group flex-nowrap">
                <span class="input-group-text"><i class="fas fa-file-invoice fs-2"></i></span>
                <input type="text" class="form-control" name="invoice_number" id="invoice_number"
                    value="{{ old('invoice_number', $invoiceNumber) }}" readonly>
            </div>
        </div>

        <div class="col-md-4">
            <label for="amount" class="form-label">{{ trans('clients.amount') }}</label>
            <div class="input-group flex-nowrap">
                <span class="input-group-text"><i class="fas fa-dollar-sign fs-2"></i></span>
                <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                       value="{{ old('amount') }}" required>
            </div>
            @error('amount')
            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="remaining_amount" class="form-label">{{ trans('clients.remaining_amount') }}</label>
            <div class="input-group flex-nowrap">
                <span class="input-group-text"><i class="fas fa-coins fs-2"></i></span>
                <input type="number" step="0.01" class="form-control" name="remaining_amount" id="remaining_amount"
                       value="{{ old('remaining_amount') }}" required>
            </div>
            @error('remaining_amount')
            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-10">
            <label for="notes" class="form-label fw-bold">{{ trans('clients.notes') }}</label>
            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="{{ trans('masrofat.enter_notes') }}">{{ old('notes') }}</textarea>
            @error('notes')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" name="add" value="add" id="add_ezn" class="btn btn-success w-100">
                <i class="bi bi-save"></i> {{ trans('employees.SaveButton') }}
            </button>
        </div>
    </div>

</form>
