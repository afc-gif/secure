<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\ActivateAccessTokenRequest;
use App\Services\TokenValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccessTokenController extends Controller
{
    public function create(): View
    {
        return view('member.batches.access-token', [
            'participations' => request()->user()
                ->batchMembers()
                ->with(['batch', 'accessToken'])
                ->latest('joined_at')
                ->get(),
        ]);
    }

    public function store(ActivateAccessTokenRequest $request, TokenValidationService $tokens): RedirectResponse
    {
        $tokens->validateAndActivate($request->user(), $request->validated('token'));

        return redirect()->route('member.participation.index')->with('status', 'Activating Batch Participation...');
    }
}
