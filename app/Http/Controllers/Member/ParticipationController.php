<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ParticipationController extends Controller
{
    public function index(): View
    {
        return view('member.participation.index', [
            'participations' => request()->user()
                ->batchMembers()
                ->with(['batch', 'accessToken'])
                ->latest('joined_at')
                ->get(),
        ]);
    }
}
