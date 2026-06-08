<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Contribution;
use App\Models\User;
use App\Services\ContributionService;
use App\Services\OwnershipAnalyticsService;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function __construct(
        private ContributionService $service,
        private OwnershipAnalyticsService $analytics
    )
    {
    }

    public function index(Request $request)
    {
        $contributions = $this->filteredContributions($request)
            ->latest()
            ->paginate(20);

        return view('admin.contributions.index', [
            'contributions' => $contributions,
            'stats' => $this->analytics->getAdminAnalytics(),
            'batches' => Batch::orderBy('title')->get(),
            'members' => User::where('role', 'member')->orderBy('name')->get(),
            'types' => Contribution::TYPES,
            'filters' => $request->only(['status', 'batch_id', 'contribution_type', 'member_id', 'search']),
        ]);
    }

    public function pending(Request $request)
    {
        $request->merge(['status' => 'pending']);

        return $this->index($request);
    }

    public function show(Contribution $contribution)
    {
        $contribution->load('user', 'batch', 'approvingAdmin');

        return view('admin.contributions.show', compact('contribution'));
    }

    public function approve(Request $request, Contribution $contribution)
    {
        $this->authorize('approve', $contribution);

        $accessToken = $this->service->confirm($contribution, $request->user(), $request->input('admin_notes'));

        return redirect()->route('admin.contributions.show', $contribution)
            ->with('success', $accessToken
                ? "Contribution approved. VIP token {$accessToken->token} was issued to {$contribution->user->name}."
                : 'Contribution approved.');
    }

    public function reject(Request $request, Contribution $contribution)
    {
        $this->authorize('reject', $contribution);

        $this->service->reject($contribution, $request->user(), $request->input('admin_notes', 'Admin review'));

        return redirect()->route('admin.contributions.index')
            ->with('warning', 'Contribution rejected.');
    }

    private function filteredContributions(Request $request)
    {
        return Contribution::query()
            ->with(['user', 'batch'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('batch_id'), fn ($query) => $query->where('batch_id', $request->batch_id))
            ->when($request->filled('contribution_type'), fn ($query) => $query->where('contribution_type', $request->contribution_type))
            ->when($request->filled('member_id'), fn ($query) => $query->where('user_id', $request->member_id))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->search;

                $query->where(function ($query) use ($search): void {
                    $query->where('payment_reference', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%"));
                });
            });
    }
}
