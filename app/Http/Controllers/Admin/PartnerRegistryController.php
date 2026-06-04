<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PartnerRegistryController extends Controller
{
    public function __invoke(): View
    {
        $partners = User::query()
            ->with(['memberProfile', 'settlementProfile', 'batchMembers.batch', 'batchMembers.accessToken'])
            ->withCount(['batchMembers as active_participations_count' => fn ($query) => $query->where('participation_status', 'active')])
            ->withSum(['contributions as confirmed_contributions_total' => fn ($query) => $query->where('status', 'confirmed')], 'amount')
            ->where('role', 'member')
            ->latest()
            ->paginate(12);

        return view('admin.partners.index', [
            'partners' => $partners,
            'totalPartners' => User::where('role', 'member')->count(),
            'activePartners' => User::where('role', 'member')->where('status', 'active')->count(),
            'activeParticipants' => User::where('role', 'member')
                ->whereHas('batchMembers', fn ($query) => $query->where('participation_status', 'active'))
                ->count(),
            'completedProfiles' => MemberProfile::where('onboarding_completed', true)->count(),
            'pendingProfiles' => User::where('role', 'member')
                ->whereDoesntHave('memberProfile', fn ($query) => $query->where('onboarding_completed', true))
                ->count(),
        ]);
    }

    public function destroy(User $partner): RedirectResponse
    {
        abort_unless($partner->isMember(), 404);

        $partnerName = $partner->name;
        $partner->delete();

        return redirect()
            ->route('admin.partners.index')
            ->with('status', "Member {$partnerName} deleted.");
    }
}
