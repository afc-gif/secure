<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\StoreContributionRequest;
use App\Models\Batch;
use App\Models\Contribution;
use App\Services\ContributionService;
use App\Services\OwnershipCalculationService;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function __construct(
        private ContributionService $service,
        private OwnershipCalculationService $ownership
    )
    {
    }

    /**
     * Display all contributions for the authenticated user.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $contributions = $user
            ->contributions()
            ->with('batch')
            ->latest()
            ->paginate(15);

        $summary = $this->ownership->calculateMemberOwnership($user);
        $recentActivity = $user->activityLogs()->latest()->take(5)->get();

        return view('member.contributions.index', compact('contributions', 'summary', 'recentActivity'));
    }

    public function history(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $contributions = $user->contributions()
            ->with('batch')
            ->latest()
            ->paginate(20);

        return view('member.contributions.history', compact('contributions'));
    }

    /**
     * Show the form to create a new contribution.
     */
    public function create(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $batches = $user->batches()->where('is_active', true)->get();
        $types = Contribution::TYPES;
        
        return view('member.contributions.create', compact('batches', 'types'));
    }

    /**
     * Store a newly created contribution.
     */
    public function store(StoreContributionRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $batch = $request->filled('batch_id') ? Batch::find($request->validated('batch_id')) : null;

        $contribution = $this->service->create(
            $user,
            $batch,
            (float) $request->validated('amount'),
            $request->validated('contribution_type'),
            $request->validated('notes'),
            $request->validated('currency')
        );

        return redirect()->route('member.contributions.show', $contribution)
            ->with('success', 'Contribution recorded. Awaiting confirmation.');
    }

    /**
     * Display a specific contribution.
     */
    public function show(Contribution $contribution)
    {
        $this->authorize('view', $contribution);

        return view('member.contributions.show', compact('contribution'));
    }
}
