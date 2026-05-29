<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompleteSettlementRequest;
use App\Models\Settlement;
use App\Services\SettlementService;

class SettlementController extends Controller
{
    public function __construct(private SettlementService $service)
    {
    }

    /**
     * Display all settlements.
     */
    public function index()
    {
        $settlements = Settlement::with('user', 'batch')
            ->latest()
            ->paginate(20);

        return view('admin.settlements.index', compact('settlements'));
    }

    /**
     * Show a specific settlement.
     */
    public function show(Settlement $settlement)
    {
        $settlement->load('user', 'batch', 'processedByAdmin');

        return view('admin.settlements.show', compact('settlement'));
    }

    /**
     * Complete a settlement and process payment.
     */
    public function complete(CompleteSettlementRequest $request, Settlement $settlement)
    {
        $this->authorize('complete', $settlement);

        /** @var \App\Models\User $admin */
        $admin = $request->user();
        $settlement->update([
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
        ]);
        $this->service->complete($settlement, $admin);

        return redirect()->route('admin.settlements.show', $settlement)
            ->with('success', 'Settlement completed successfully.');
    }

    /**
     * Reject a settlement.
     */
    public function reject(Settlement $settlement)
    {
        $this->authorize('reject', $settlement);

        $settlement->fail();

        return redirect()->route('admin.settlements.index')
            ->with('warning', 'Settlement rejected.');
    }
}
