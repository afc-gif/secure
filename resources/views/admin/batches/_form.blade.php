@csrf

<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="title" class="cca-label">Batch Title</label>
        <input id="title" name="title" value="{{ old('title', $batch->title) }}" class="cca-input mt-2" required>
        <x-input-error :messages="$errors->get('title')" class="mt-2 text-rose-300" />
    </div>

    <div class="sm:col-span-2">
        <label for="description" class="cca-label">Description</label>
        <textarea id="description" name="description" rows="4" class="cca-input mt-2">{{ old('description', $batch->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2 text-rose-300" />
    </div>

    <div>
        <label for="start_date" class="cca-label">Start Date</label>
        <input id="start_date" type="date" name="start_date" value="{{ old('start_date', $batch->start_date?->format('Y-m-d')) }}" class="cca-input mt-2">
        <x-input-error :messages="$errors->get('start_date')" class="mt-2 text-rose-300" />
    </div>

    <div>
        <label for="end_date" class="cca-label">End Date</label>
        <input id="end_date" type="date" name="end_date" value="{{ old('end_date', $batch->end_date?->format('Y-m-d')) }}" class="cca-input mt-2">
        <x-input-error :messages="$errors->get('end_date')" class="mt-2 text-rose-300" />
    </div>

    <div>
        <label for="status" class="cca-label">Status</label>
        <select id="status" name="status" class="cca-input mt-2" required>
            @foreach (\App\Models\Batch::STATUSES as $status)
                <option value="{{ $status }}" @selected(old('status', $batch->status) === $status)>{{ Str::of($status)->title() }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="ownership_level" class="cca-label">Ownership Level</label>
        <input id="ownership_level" name="ownership_level" value="{{ old('ownership_level', $batch->ownership_level) }}" class="cca-input mt-2" required>
    </div>

    <div>
        <label for="max_members" class="cca-label">Max Members</label>
        <input id="max_members" type="number" min="0" name="max_members" value="{{ old('max_members', $batch->max_members ?? 0) }}" class="cca-input mt-2" required>
        <x-input-error :messages="$errors->get('max_members')" class="mt-2 text-rose-300" />
    </div>

    <div>
        <label for="participation_fee" class="cca-label">Participation Fee (USD)</label>
        <input id="participation_fee" type="number" min="0" step="0.01" name="participation_fee" value="{{ old('participation_fee', $batch->participation_fee) }}" class="cca-input mt-2">
    </div>
</div>

<label class="mt-6 flex gap-3 rounded-lg border border-emerald-300/20 bg-emerald-300/10 p-4 text-sm text-emerald-100">
    <input type="checkbox" name="is_active" value="1" class="mt-1 rounded border-white/20 bg-black/40 text-emerald-400 focus:ring-emerald-300" @checked(old('is_active', $batch->is_active))>
    <span>Open this batch for cooperative access token activation.</span>
</label>

<div class="mt-6 flex flex-col-reverse gap-3 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
    <a href="{{ route('admin.batches.index') }}" class="cca-muted-button">Back</a>
    <button class="cca-button">Save Ownership Batch</button>
</div>
