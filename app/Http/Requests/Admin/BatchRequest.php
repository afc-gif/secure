<?php

namespace App\Http\Requests\Admin;

use App\Models\Batch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $batchId = $this->route('batch')?->id;

        return [
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1200'],
            'batch_code' => [
                'nullable',
                'string',
                'max:80',
                Rule::unique('batches', 'batch_code')->ignore($batchId),
            ],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(Batch::STATUSES)],
            'max_members' => ['required', 'integer', 'min:0', 'max:1000000'],
            'ownership_level' => ['required', 'string', 'max:80'],
            'participation_fee' => ['nullable', 'numeric', 'min:0', 'max:999999999.99'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'batch_code' => filled($this->input('batch_code')) ? trim($this->input('batch_code')) : null,
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
