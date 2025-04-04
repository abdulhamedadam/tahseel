<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Account;
use App\Models\Admin\AccountSettings;
use App\Models\Admin\AccountTransfer;
use App\Models\Admin\FinancialTransaction;
use App\Models\Admin\Masrofat;
use App\Models\Admin\SarfBand;
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
    protected $bandsRepository;
    protected $masrofatRepository;

    public function __construct(BasicRepositoryInterface $basicRepository)
    {
        $this->middleware('can:view_account_transfers', ['only' => ['account_transfers', 'get_ajax_account_transfers']]);
        $this->middleware('can:create_account_transfer', ['only' => ['add_account_transfer']]);
        $this->middleware('can:redo_account_transfer', ['only' => ['redo_account_transfer']]);

        $this->accountTransfersRepository = createRepository($basicRepository, new AccountTransfer());
        $this->accountsRepository = createRepository($basicRepository, new Account());
        $this->bandsRepository = createRepository($basicRepository, new SarfBand());
        $this->masrofatRepository   = createRepository($basicRepository, new Masrofat());


    }

    public function account_transfers()
    {
        $data['accounts'] = $this->accountsRepository->getAll();
        $data['bands'] = $this->bandsRepository->getAll();
        $data['masrofatAccountId'] = AccountSettings::first()->masrofat_account_id ?? null;

        return view('dashbord.accounts.account_transfers.index', $data);
    }

    public function get_ajax_account_transfers()
    {
        if (request()->ajax()) {
            try {
                $data = $this->accountTransfersRepository->getAll()->toQuery()->where('deleted_at', null)->orderBy('created_at', 'desc')->get();

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
                        if (auth()->user()->can('redo_account_transfer')) {
                            $actionButtons .= '<form action="' . route('admin.redo_account_transfer', $row->id) . '" method="POST" style="display:inline;">
                                                ' . csrf_field() . '
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to redo this transfer?\')">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>';

                        }
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
            'band_id' => 'nullable|exists:tbl_sarf_bands,id',
        ]);

        try {
            $transferData = [
                'from_account' => $validated_data['from_account'],
                'to_account' => $validated_data['to_account'],
                'amount' => $validated_data['amount'],
                'notes' => $validated_data['notes'],
                'date' => now()->toDateString(),
                'time' => now()->toTimeString(),
                'month' => now()->month,
                'year' => now()->year,
                'created_by' => Auth::id(),
            ];

            $fromAccountName = Account::find($validated_data['from_account'])->name ?? 'Unknown';
            $toAccountName = Account::find($validated_data['to_account'])->name ?? 'Unknown';

            // dd($validated_data);
            $this->accountTransfersRepository->create($transferData);

            $masrofatAccountId = AccountSettings::first()->masrofat_account_id ?? null;
            // dd($validated_data, $masrofatAccountId);
            if ($validated_data['to_account'] == $masrofatAccountId && !empty($validated_data['band_id'])) {
                // dd('ssss');
                $masrofatData = [
                    'emp_id' => Auth::id(),
                    'band_id' => $validated_data['band_id'],
                    'value' => $validated_data['amount'],
                    'notes' => $validated_data['notes'],
                    'created_by' => Auth::id(),
                ];
                // dd($masrofatData);
                $this->masrofatRepository->create($masrofatData);
            }

            FinancialTransaction::create([
                'account_id'    => $validated_data['from_account'],
                'amount'        => -$validated_data['amount'],
                'date'          => now()->toDateString(),
                'time'          => now()->toTimeString(),
                'month'         => now()->month,
                'year'          => now()->year,
                'notes'         => "تحويل مالي من الحساب {$fromAccountName} إلى الحساب {$toAccountName} / {$validated_data['notes']}",
                'type'          => 'sarf',
                'created_by'    => Auth::id(),
            ]);

            FinancialTransaction::create([
                'account_id'    => $validated_data['to_account'],
                'amount'        => $validated_data['amount'],
                'date'          => now()->toDateString(),
                'time'          => now()->toTimeString(),
                'month'         => now()->month,
                'year'          => now()->year,
                'notes'         => "تحويل مالي إلى الحساب {$toAccountName} من الحساب {$fromAccountName} / {$validated_data['notes']}",
                'type'          => 'qapd',
                'created_by'    => Auth::id(),
            ]);

            toastr()->addSuccess(trans('account_transfers.transfer_added_successfully'));
            return redirect()->route('admin.account_transfers');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function redo_account_transfer($id)
    {
        try {
            $accountTransfer = $this->accountTransfersRepository->getById($id);
            $masrofatAccountId = AccountSettings::first()->masrofat_account_id ?? null;

            if ($accountTransfer) {
                $createdAt = $accountTransfer->created_at;

                FinancialTransaction::where('account_id', $accountTransfer->from_account)
                        ->where('amount', -$accountTransfer->amount)
                        ->whereBetween('created_at', [$createdAt->subSeconds(3), $createdAt->addSeconds(3)])
                        ->delete();

                FinancialTransaction::where('account_id', $accountTransfer->to_account)
                        ->where('amount', $accountTransfer->amount)
                        ->whereBetween('created_at', [$createdAt->subSeconds(3), $createdAt->addSeconds(3)])
                        ->delete();

                if ($accountTransfer->to_account == $masrofatAccountId) {
                    Masrofat::where('value', $accountTransfer->amount)
                            ->whereBetween('created_at', [$createdAt->subSeconds(3), $createdAt->addSeconds(3)])
                            ->delete();
                }

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
