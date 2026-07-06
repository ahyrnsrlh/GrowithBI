<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLogbookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $logbook = $this->route('logbook');
        return $logbook && $logbook->canBeEditedBy($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'date' => 'required|date|before_or_equal:today',
            'activities' => 'required|string|min:10',
            'learning_points' => 'nullable|string',
            'challenges' => 'nullable|string',
            'duration' => 'required|numeric|min:0.5|max:24',
            'status' => 'required|in:draft,submitted',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB
            'removed_files' => 'nullable|array',
            'removed_files.*' => 'nullable|string'
        ];
    }
}
