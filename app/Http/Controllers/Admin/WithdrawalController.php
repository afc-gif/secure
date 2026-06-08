<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettlementProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    public function index(Request $request): View
    {
        $withdrawals = SettlementProfile::query()
            ->with('user.memberProfile')
            ->whereNotNull('withdrawal_status')
            ->when($request->filled('status'), fn ($query) => $query->where('withdrawal_status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->search;

                $query->where(function ($query) use ($search): void {
                    $query->where('account_name', 'like', "%{$search}%")
                        ->orWhere('bank_name', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->orderByRaw("withdrawal_status = 'processing' desc")
            ->latest('withdrawal_requested_at')
            ->paginate(20);

        return view('admin.withdrawals.index', [
            'withdrawals' => $withdrawals,
            'filters' => $request->only(['status', 'search']),
            'processingCount' => SettlementProfile::where('withdrawal_status', 'processing')->count(),
            'processingTotal' => SettlementProfile::where('withdrawal_status', 'processing')->sum('withdrawal_amount'),
            'completedCount' => SettlementProfile::where('withdrawal_status', 'completed')->count(),
            'completedTotal' => SettlementProfile::where('withdrawal_status', 'completed')->sum('total_withdrawn_amount'),
        ]);
    }
}
