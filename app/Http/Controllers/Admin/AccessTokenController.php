<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccessTokenRequest;
use App\Models\AccessToken;
use App\Models\Batch;
use App\Models\User;
use App\Services\TokenGenerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccessTokenController extends Controller
{
    public function index(): View
    {
        return view('admin.tokens.index', [
            'tokens' => AccessToken::query()
                ->with(['batch', 'assignedUser'])
                ->latest()
                ->paginate(15),
            'activeTokens' => AccessToken::where('status', 'active')->count(),
            'usedTokens' => AccessToken::where('status', 'used')->count(),
            'revokedTokens' => AccessToken::where('status', 'revoked')->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.tokens.create', [
            'batches' => Batch::query()->latest()->get(),
            'members' => User::query()->where('role', 'member')->orderBy('name')->get(),
        ]);
    }

    public function store(AccessTokenRequest $request, TokenGenerationService $tokens): RedirectResponse
    {
        $data = $request->validated();
        $batch = Batch::findOrFail($data['batch_id']);

        if (($data['quantity'] ?? 1) > 1) {
            $created = $tokens->bulkCreate($batch, $request->user(), $data);

            return redirect()->route('admin.tokens.index')->with('status', "{$created->count()} secure access tokens generated.");
        }

        $tokens->create($batch, $request->user(), $data);

        return redirect()->route('admin.tokens.index')->with('status', 'Secure access token generated.');
    }

    public function revoke(AccessToken $token): RedirectResponse
    {
        if ($token->status === 'used') {
            return back()->withErrors(['token' => 'Used tokens cannot be revoked after participation activation.']);
        }

        $token->forceFill([
            'status' => 'revoked',
            'revoked_at' => now(),
        ])->save();

        return back()->with('status', 'Secure access token revoked.');
    }
}
