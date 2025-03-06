<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Account;
use App\Models\Admin\AccountTransfer;
use App\Models\Admin\FinancialTransaction;
use App\Traits\ValidationMessage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class AccountTransferController extends Controller
{
    use ValidationMessage;

    protected $accountTransfersRepository;
    protected $accountTransferService;
    protected $accountsRepository;

    public function __construct(BasicRepositoryInterface $basicRepository)
    {
        $this->accountTransfersRepository = createRepository($basicRepository, new AccountTransfer());
        $this->accountsRepository = createRepository($basicRepository, new Account());
    }

    public function account_transfers()
    {
        $data['accounts'] = $this->accountsRepository->getAll();
        return view('dashbord.accounts.account_transfers.index', $data);
    }

    public function get_ajax_account_transfers()
    {
        if (request()->ajax()) {
            try {
                $data = $this->accountTransfersRepository->getAll();

                return DataTables::of($data)
                    ->addColumn('id', function ($row) {
                        return $row->id;
                    })
                    ->addColumn('from_account', function ($row) {
                        return $row->fromAccount->name ?? 'N/A';
                    })
                    ->addColumn('to_account', function ($row) {
                        return $row->toAccount->name ?? 'N/A';
                    })
                    ->addColumn('amount', function ($row) {
                        return number_format($row->amount, 2);
                    })
                    ->addColumn('date', function ($row) {
                        return $row->date ?? 'N/A';
                    })
                    ->addColumn('created_by', function ($row) {
                        return $row->admin->name ?? 'N/A';
                    })
                    ->addColumn('action', function ($row) {
                        $actionButtons = '';

                        // $actionButtons .= '<a data-bs-toggle="modal" data-bs-target="#modalAccountTransfers" onclick="editAccountTransfer(' . $row->id . ')" class="btn btn-sm btn-warning" title="Edit">
                        //         <i class="bi bi-pencil"></i>
                        //     </a>';

                        // $actionButtons .= '<a onclick="return confirm(\'Are You Sure To Delete?\')" href="' . route('admin.delete_account_transfer', $row->id) . '"  class="btn btn-sm btn-danger">
                        //         <i class="bi bi-trash"></i>
                        //     </a>';

                        // $actionButtons .= '<a onclick="return confirm(\'Are you sure you want to redo this transfer?\')" href=" ' . route('admin.redo_account_transfer', $row->id) . '" class="btn btn-sm btn-danger">
                        //                     <i class="bi bi-arrow-counterclockwise"></i>
                        //                 </a>';

                        $actionButtons .= '<form action="' . route('admin.redo_account_transfer', $row->id) . '" method="POST" style="display:inline;">
                                                ' . csrf_field() . '
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to redo this transfer?\')">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>';


                        return $actionButtons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }
    }

    public function add_account_transfer(Request $request)
    {
        $validated_data = $request->validate([
            'from_account' => 'required|exists:tbl_accounts,id',
            'to_account' => 'required|exists:tbl_accounts,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $validated_data['date'] = now()->toDateString();
            $validated_data['time'] = now()->toTimeString();
            $validated_data['month'] = now()->month;
            $validated_data['year'] = now()->year;
            $validated_data['created_by'] = Auth::id();

            // dd($validated_data);
            $this->accountTransfersRepository->create($validated_data);

            FinancialTransaction::create([
                'account_id'    => $validated_data['from_account'],
                'amount'        => -$validated_data['amount'],
                'date'          => $validated_data['date'],
                'time'          => $validated_data['time'],
                'month'         => $validated_data['month'],
                'year'          => $validated_data['year'],
                'notes'         => 'تحويل مالي من الحساب ' . $validated_data['from_account'] . ' إلى الحساب ' . $validated_data['to_account'] . ' / ' . $validated_data['notes'],
                'type'          => 'sarf',
                'created_by'    => Auth::id(),
            ]);

            FinancialTransaction::create([
                'account_id'    => $validated_data['to_account'],
                'amount'        => $validated_data['amount'],
                'date'          => $validated_data['date'],
                'time'          => $validated_data['time'],
                'month'         => $validated_data['month'],
                'year'          => $validated_data['year'],
                'notes'         => 'تحويل مالي إلى الحساب ' . $validated_data['to_account'] . ' من الحساب ' . $validated_data['from_account'] . ' / ' . $validated_data['notes'],
                'type'          => 'qapd',
                'created_by'    => Auth::id(),
            ]);

            toastr()->addSuccess(trans('account_transfers.transfer_added_successfully'));
            return redirect()->route('admin.account_transfers');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // public function edit_account_transfer($id)
    // {
    //     $data['transfer'] = $this->accountTransfersRepository->getById($id);
    //     return response()->json($data);
    // }

    // public function update(Request $request, $id)
    // {
    //     $validated_data = $request->validate([
    //         'from_account' => 'required|exists:tbl_accounts,id',
    //         'to_account' => 'required|exists:tbl_accounts,id',
    //         'amount' => 'required|numeric|min:0',
    //         'date' => 'nullable|date',
    //         'time' => 'nullable|date_format:H:i',
    //         'month' => 'nullable|integer',
    //         'year' => 'nullable|integer',
    //         'notes' => 'nullable|string',
    //     ]);

    //     try {
    //         $validated_data['updated_by'] = Auth::id();
    //         $this->accountTransfersRepository->update($id, $validated_data);

    //         toastr()->addSuccess(trans('account_transfers.transfer_updated_successfully'));
    //         return redirect()->route('admin.account_transfers');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    //     }
    // }

    // public function destroy($id)
    // {
    //     try {
    //         $this->accountTransfersRepository->delete($id);
    //         toastr()->addSuccess(trans('account_transfers.transfer_deleted_successfully'));
    //         return redirect()->route('admin.account_transfers');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    //     }
    // }
    public function redo_account_transfer($id)
    {
        try {
            $accountTransfer = $this->accountTransfersRepository->getById($id);

            if ($accountTransfer) {
                FinancialTransaction::where('account_id', $accountTransfer->from_account)
                    ->where('amount', -$accountTransfer->amount)
                    ->where('notes', 'like', '%تحويل مالي من الحساب ' . $accountTransfer->from_account . '%')
                    ->delete();

                FinancialTransaction::where('account_id', $accountTransfer->to_account)
                    ->where('amount', $accountTransfer->amount)
                    ->where('notes', 'like', '%تحويل مالي إلى الحساب ' . $accountTransfer->to_account . '%')
                    ->delete();

                $this->accountTransfersRepository->delete($id);

                toastr()->addSuccess(trans('account_transfers.transfer_redone_successfully'));
            } else {
                toastr()->addError(trans('account_transfers.transfer_not_found'));
            }

            return redirect()->route('admin.account_transfers');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
