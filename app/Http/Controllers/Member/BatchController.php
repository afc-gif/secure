<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\View\View;

class BatchController extends Controller
{
    public function index(): View
    {
        return view('member.batches.index', [
            'activeBatches' => Batch::query()
                ->where('is_active', true)
                ->where('status', 'active')
                ->latest('start_date')
                ->get(),
            'participations' => request()->user()
                ->batchMembers()
                ->with(['batch', 'accessToken'])
                ->latest('joined_at')
                ->get(),
        ]);
    }
}
