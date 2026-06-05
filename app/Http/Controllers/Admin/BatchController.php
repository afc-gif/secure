<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BatchRequest;
use App\Models\Batch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BatchController extends Controller
{
    public function index(): View
    {
        return view('admin.batches.index', [
            'batches' => Batch::query()
                ->withCount(['accessTokens', 'batchMembers'])
                ->latest()
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.batches.create', [
            'batch' => new Batch(['status' => 'active', 'ownership_level' => 'Catalog Equity Class', 'is_active' => true]),
        ]);
    }

    public function store(BatchRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(5));
        $data['batch_code'] = $data['batch_code'] ?: 'SECURE-CYCLE-'.Str::upper(Str::random(6));

        Batch::create($data);

        return redirect()->route('admin.batches.index')->with('status', 'Active cycle created and synced to the database.');
    }

    public function edit(Batch $batch): View
    {
        return view('admin.batches.edit', compact('batch'));
    }

    public function update(BatchRequest $request, Batch $batch): RedirectResponse
    {
        $data = $request->validated();

        if (! $data['batch_code']) {
            unset($data['batch_code']);
        }

        $batch->update($data);

        return redirect()->route('admin.batches.index')->with('status', 'Active cycle updated and synced to the database.');
    }

    public function destroy(Batch $batch): RedirectResponse
    {
        $batch->delete();

        return redirect()->route('admin.batches.index')->with('status', 'Active cycle deleted from the database.');
    }

    public function archive(Batch $batch): RedirectResponse
    {
        $batch->update([
            'status' => 'archived',
            'is_active' => false,
        ]);

        return back()->with('status', 'Active cycle archived.');
    }
}
